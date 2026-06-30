export function healthCheck(req, res) {
  res.json({ status: 'ok', service: 'express', timestamp: new Date().toISOString() });
}
