# Paperjet Laravel SDK (`paperjet/laravel-sdk`)

Drop-in `mPDF` and `DomPDF` replacement for PHP & Laravel applications that render messy CKEditor HTML, Word-pasted markup, multi-page dynamic tables, and embedded `Chart.js` canvases.

## 60-Second Migration from mPDF

```bash
composer require paperjet/laravel-sdk
```

Add your API key to `.env`:

```env
PAPERJET_API_KEY=pjet_live_xxxxxxxxxxxxxxxxxxxxxxxx
```

Replace your legacy `mPDF` boilerplate:

```diff
- $mpdf = new \Mpdf\Mpdf(['tempDir' => storage_path('temp')]);
- $mpdf->WriteHTML($ckeditorHtml);
- return $mpdf->Output('invoice.pdf', 'D');
+ use Paperjet\Sdk\Facades\Paperjet;
+
+ return Paperjet::fromCkEditor($ckeditorHtml)
+     ->withChartJs()
+     ->paper('A4', 'portrait')
+     ->download('invoice.pdf');
```

## JavaScript Chart Libraries (Chart.js, ApexCharts, ECharts, Highcharts)

Paperjet is powered by headless Chromium with a full V8 engine and supports **all** modern JavaScript chart libraries.

* **Chart.js**: Handled automatically when calling `->withChartJs()`. Paperjet intercepts the runtime to enforce `Chart.defaults.animation = false` and 2x DPI capture.
* **Other Libraries (ApexCharts, ECharts, Highcharts, Plotly)**: Supported natively. Ensure you disable entrance animations so the chart renders synchronously on frame 0:

| Library | 0ms Synchronous Print Configuration |
| :--- | :--- |
| **ApexCharts** | `chart: { animations: { enabled: false } }` |
| **Apache ECharts** | `animation: false` |
| **Highcharts** | `plotOptions: { series: { animation: false } }` |
| **Plotly.js** | `Plotly.newPlot(elem, data, layout, { staticPlot: true })` |

