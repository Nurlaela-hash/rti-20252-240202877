import os
import re
import numpy as np

# We'll import scipy.stats. If it's not installed, we can implement calculations manually.
try:
    from scipy import stats
except ImportError:
    stats = None

def parse_file(file_path):
    try:
        content = open(file_path, 'rb').read()
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
            return None
        
        metrics = {}
        duration_line = None
        for line in text.splitlines():
            if 'http_req_duration' in line and '{' not in line:
                duration_line = line
                break
        if duration_line:
            matches = re.findall(r'(min|avg|med|p\(90\)|p\(95\)|p\(99\))\s*=\s*([\d\.]+)\s*([^\s,\{\}]+)?', duration_line)
            for m in matches:
                name, val_str, unit = m
                val = float(val_str)
                if unit:
                    unit_clean = unit.strip()
                    if unit_clean == 's':
                        val *= 1000.0
                    elif 'µs' in unit_clean or '╡' in unit_clean:
                        val /= 1000.0
                else:
                    if val > 100:
                        val /= 1000.0
                key_name = name.replace('(', '').replace(')', '')
                metrics[f'duration_{key_name}'] = val
            
        reqs_match = re.search(r'http_reqs\.*:\s*(\d+)\s+([\d\.]+)/s', text)
        if reqs_match:
            metrics['reqs_total'] = int(reqs_match.group(1))
            metrics['reqs_rps'] = float(reqs_match.group(2))
            
        failed_match = re.search(r'http_req_failed\.*:\s*([\d\.]+)%\s+(\d+)\s+out\s+of\s+(\d+)', text)
        if failed_match:
            metrics['failed_pct'] = float(failed_match.group(1))
            metrics['failed_count'] = int(failed_match.group(2))
            
        return metrics
    except Exception as e:
        print(f"Error: {e}")
        return None

def compute_cohens_d(x, y):
    nx = len(x)
    ny = len(y)
    dof = nx + ny - 2
    var_x = np.var(x, ddof=1)
    var_y = np.var(y, ddof=1)
    # Pooled standard deviation
    pooled_std = np.sqrt(((nx - 1) * var_x + (ny - 1) * var_y) / dof)
    # Cohen's d
    d = (np.mean(x) - np.mean(y)) / pooled_std
    return d

def compute_ci_diff(x, y):
    nx = len(x)
    ny = len(y)
    mean_x = np.mean(x)
    mean_y = np.mean(y)
    var_x = np.var(x, ddof=1)
    var_y = np.var(y, ddof=1)
    
    # Standard error of difference
    se_diff = np.sqrt(var_x / nx + var_y / ny)
    diff = mean_x - mean_y
    
    # Welch-Satterthwaite equation for degrees of freedom
    numerator = (var_x / nx + var_y / ny) ** 2
    denominator = ((var_x / nx) ** 2) / (nx - 1) + ((var_y / ny) ** 2) / (ny - 1)
    df = numerator / denominator
    
    # critical t value for 95% confidence interval
    # standard fallback t-value if scipy not available: df ~ 18, t_crit ~ 2.101
    if stats:
        t_crit = stats.t.ppf(0.975, df)
    else:
        t_crit = 2.101 # default for df=18, alpha=0.05 two-tailed
        
    margin_error = t_crit * se_diff
    return diff - margin_error, diff + margin_error

def main():
    script_dir = os.path.dirname(os.path.abspath(__file__))
    logs_dir = os.path.join(script_dir, "experiment-logs")
    
    express_runs = []
    laravel_runs = []
    
    for i in range(1, 11):
        express_file = os.path.join(logs_dir, f"run-express-{i:02d}.txt")
        laravel_file = os.path.join(logs_dir, f"run-laravel-{i:02d}.txt")
        
        e_metrics = parse_file(express_file)
        if e_metrics: express_runs.append(e_metrics)
        
        l_metrics = parse_file(laravel_file)
        if l_metrics: laravel_runs.append(l_metrics)
        
    print(f"Loaded {len(express_runs)} Express runs and {len(laravel_runs)} Laravel runs.\n")
    
    metrics_to_test = ['reqs_rps', 'duration_avg', 'duration_p95']
    
    for m in metrics_to_test:
        print(f"================ STATISTICAL TEST FOR: {m} ================")
        e_vals = np.array([r[m] for r in express_runs])
        l_vals = np.array([r[m] for r in laravel_runs])
        
        print(f"Express   : mean = {np.mean(e_vals):.4f}, std = {np.std(e_vals, ddof=1):.4f}")
        print(f"Laravel 11: mean = {np.mean(l_vals):.4f}, std = {np.std(l_vals, ddof=1):.4f}")
        
        # Test Normality
        if stats:
            _, p_shapiro_e = stats.shapiro(e_vals)
            _, p_shapiro_l = stats.shapiro(l_vals)
            print(f"Shapiro Normality p-val: Express = {p_shapiro_e:.4f}, Laravel = {p_shapiro_l:.4f}")
            
            # Run t-test (independent)
            t_stat, p_val = stats.ttest_ind(e_vals, l_vals, equal_var=False)
            print(f"Welch's t-test         : t = {t_stat:.4f}, p = {p_val:.4e}")
            
            # Mann-Whitney U test
            u_stat, p_mwu = stats.mannwhitneyu(e_vals, l_vals)
            print(f"Mann-Whitney U test    : U = {u_stat:.4f}, p = {p_mwu:.4e}")
        else:
            print("Scipy not available, running basic calculations.")
            
        # Cohen's d
        d = compute_cohens_d(e_vals, l_vals)
        ci_low, ci_high = compute_ci_diff(e_vals, l_vals)
        print(f"Cohen's d (E vs L)     : {d:.4f}")
        print(f"95% CI of Difference   : [{ci_low:.4f}, {ci_high:.4f}]")
        print()

if __name__ == '__main__':
    main()
