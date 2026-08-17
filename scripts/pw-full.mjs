import { chromium } from 'playwright';

const BASE = process.env.BASE_URL || 'http://localhost:8000';
const EMAIL = process.env.POS_EMAIL || 'admin@pos.com';
const PASS = process.env.POS_PASS || 'password';

const ROUTES = ['/orders', '/products', '/categories', '/units', '/tables',
                '/product-variants', '/sales', '/purchases', '/users'];

const browser = await chromium.launch();
const ctx = await browser.newContext();
const page = await ctx.newPage();

// ---- 1. Login ----
await page.goto(`${BASE}/`, { waitUntil: 'networkidle' });
await page.getByPlaceholder('Email').fill(EMAIL);
await page.getByPlaceholder('Password').fill(PASS);
await page.getByRole('button', { name: /sign in/i }).click();
await page.waitForURL(/\/orders/, { timeout: 8000 }).catch(() => {});
const loggedIn = !page.url().endsWith('/login');
console.log(`LOGIN: ${loggedIn ? 'OK ✅' : 'FAILED ✗'} (${page.url()})`);

// ---- 2. Visit every page, capture per-route errors ----
const results = [];
for (const route of ROUTES) {
  const errors = [], bad = [];
  const onErr = (m) => m.type() === 'error' && errors.push(m.text());
  const onBad = (r) => r.status() >= 400 && bad.push(`${r.status()} ${r.request().method()} ${new URL(r.url()).pathname}`);
  page.on('console', onErr);
  page.on('response', onBad);
  await page.goto(`${BASE}${route}`, { waitUntil: 'networkidle', timeout: 20000 }).catch(() => {});
  await page.waitForTimeout(700);
  const bodyLen = (await page.locator('#app').innerText().catch(() => '')).trim().length;
  page.off('console', onErr);
  page.off('response', onBad);
  results.push({ route, url: page.url(), bodyLen, errors: [...errors], bad: [...bad] });
}

// ---- 3. Image upload (exercises intervention/image v2 on Laravel 13) ----
console.log('\n--- Image upload test (intervention/image) ---');
const upload = await page.evaluate(async () => {
  const xsrf = decodeURIComponent((document.cookie.match(/XSRF-TOKEN=([^;]+)/) || [])[1] || '');
  // find a valid product_id
  const pr = await fetch('/api/products', { headers: { Accept: 'application/json' }, credentials: 'include' });
  const pj = await pr.json().catch(() => ({}));
  const product_id = (pj.data && pj.data[0] && pj.data[0].id) || 1;

  // generate a 100x100 PNG in-browser
  const canvas = document.createElement('canvas');
  canvas.width = canvas.height = 100;
  const g = canvas.getContext('2d');
  g.fillStyle = '#3b82f6'; g.fillRect(0, 0, 100, 100);
  const blob = await new Promise((res) => canvas.toBlob(res, 'image/png'));

  const fd = new FormData();
  fd.append('name', 'PW Test Variant ' + Date.now());
  fd.append('rate', '25');
  fd.append('product_id', String(product_id));
  fd.append('quantity', '5');
  fd.append('attachment', blob, 'pw-test.png');

  const resp = await fetch('/api/product-variant/store', {
    method: 'POST',
    headers: { Accept: 'application/json', 'X-XSRF-TOKEN': xsrf },
    body: fd,
    credentials: 'include',
  });
  const text = await resp.text();
  return { status: resp.status, product_id, body: text.slice(0, 300) };
});
console.log(`  product_id used: ${upload.product_id}`);
console.log(`  POST /api/product-variant/store -> ${upload.status} ${upload.status === 201 ? '✅ (image processed & saved)' : '✗'}`);
console.log(`  response: ${upload.body}`);

// ---- Report ----
console.log('\n===== PER-PAGE REPORT =====');
let clean = 0;
for (const r of results) {
  const ok = r.errors.length === 0 && r.bad.length === 0 && !r.url.endsWith('/login') && r.bodyLen > 0;
  if (ok) clean++;
  console.log(`${ok ? '✅' : '⚠️ '} ${r.route.padEnd(18)} -> ${r.url.replace(BASE, '')}  [${r.bodyLen} chars]`);
  r.errors.slice(0, 3).forEach((e) => console.log(`      console: ${e}`));
  r.bad.slice(0, 5).forEach((e) => console.log(`      http: ${e}`));
}
console.log(`\nClean pages: ${clean}/${results.length}`);

await browser.close();
