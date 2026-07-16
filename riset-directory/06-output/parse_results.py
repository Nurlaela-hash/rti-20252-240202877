import os
import re
import numpy as np

def parse_file(file_path):
    try:
        content = open(file_path, 'rb').read()
        
        # Try decoding with different encodings
        text = None
        for enc in ['utf-16-le', 'utf-16-be', 'utf-8']:
            try:
                candidate = content.decode(enc, errors='ignore')
                if 'http_req_duration' in candidate:
                    text = candidate
                    break
            except Exception:
                continue
                
        if not text:
            print(f"Failed to find http_req_duration in {file_path} with any encoding")
            return None
        
        metrics = {}
        
        # Parse http_req_duration line
        duration_line = None
        for line in text.splitlines():
            if 'http_req_duration' in line and '{' not in line:
                duration_line = line
                break
                
        if duration_line:
            # find min, avg, med, p(90), p(95), p(99)
            # e.g. min=619.59,╡╳ avg=4.38s
            matches = re.findall(r'(min|avg|med|p\(90\)|p\(95\)|p\(99\))\s*=\s*([\d\.]+)\s*([^\s,\{\}]+)?', duration_line)
            for m in matches:
                name, val_str, unit = m
                val = float(val_str)
                if unit:
                    unit_clean = unit.strip()
                    if unit_clean == 's':
                        val *= 1000.0
                    elif 'µs' in unit_clean or '╡' in unit_clean or '' in unit_clean:
                        val /= 1000.0
                else:
                    # if no unit, check if raw value is very large (microsecs)
                    if val > 100:
                        val /= 1000.0
                
                # rename p(95) to p95
                key_name = name.replace('(', '').replace(')', '')
                metrics[f'duration_{key_name}'] = val
            
        # http_reqs......................: 156     2.159265/s
        reqs_match = re.search(r'http_reqs\.*:\s*(\d+)\s+([\d\.]+)/s', text)
        if reqs_match:
            metrics['reqs_total'] = int(reqs_match.group(1))
            metrics['reqs_rps'] = float(reqs_match.group(2))
            
        # http_req_failed................: 0.00%   0 out of 156
        failed_match = re.search(r'http_req_failed\.*:\s*([\d\.]+)%\s+(\d+)\s+out\s+of\s+(\d+)', text)
        if failed_match:
            metrics['failed_pct'] = float(failed_match.group(1))
            metrics['failed_count'] = int(failed_match.group(2))
            
        # iterations.....................: 127     1.757863/s
        iter_match = re.search(r'iterations\.*:\s*(\d+)\s+([\d\.]+)/s', text)
        if iter_match:
            metrics['iterations_total'] = int(iter_match.group(1))
            metrics['iterations_rps'] = float(iter_match.group(2))
            
        return metrics
    except Exception as e:
        print(f"Error parsing {file_path}: {e}")
        return None

def main():
    # Dynamic path based on script location to support different operating systems
    script_dir = os.path.dirname(os.path.abspath(__file__))
    logs_dir = os.path.join(script_dir, "experiment-logs")
    
    express_runs = []
    laravel_runs = []
    
    for i in range(1, 11):
        express_file = os.path.join(logs_dir, f"run-express-{i:02d}.txt")
        laravel_file = os.path.join(logs_dir, f"run-laravel-{i:02d}.txt")
        
        e_metrics = parse_file(express_file)
        if e_metrics and 'duration_avg' in e_metrics:
            express_runs.append(e_metrics)
        else:
            print(f"Missing metrics in {express_file}")
            
        l_metrics = parse_file(laravel_file)
        if l_metrics and 'duration_avg' in l_metrics:
            laravel_runs.append(l_metrics)
        else:
            print(f"Missing metrics in {laravel_file}")
            
    print(f"Loaded {len(express_runs)} Express runs, {len(laravel_runs)} Laravel runs.\n")
    
    def print_summary(name, runs):
        print(f"=== {name} SUMMARY ===")
        if not runs:
            print("No data.")
            return
        
        keys = ['duration_avg', 'duration_med', 'duration_p95', 'reqs_rps', 'reqs_total', 'failed_pct']
        for k in keys:
            vals = [r[k] for r in runs if k in r]
            if vals:
                print(f"{k:<15}: mean = {np.mean(vals):.3f}, std = {np.std(vals):.3f}, min = {np.min(vals):.3f}, max = {np.max(vals):.3f}")
        print()

    print_summary("EXPRESS.JS", express_runs)
    print_summary("LARAVEL", laravel_runs)

if __name__ == '__main__':
    main()
