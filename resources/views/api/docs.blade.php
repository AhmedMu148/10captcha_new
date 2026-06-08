<x-app-layout>

@push('styles')
<style>
pre { background:#f4f4f4; border:1px solid #ddd; border-radius:4px; padding:12px 16px; overflow-x:auto; font-size:0.82rem; line-height:1.6; margin:10px 0 10px 16px; }
code { font-family:'Courier New',Courier,monospace; font-size:0.85em; background:#f4f4f4; padding:1px 4px; border-radius:3px; }
pre code { background:none; padding:0; font-size:inherit; }
table { width:100%; border-collapse:collapse; margin:12px 0 12px 16px; font-size:0.875rem; }
table th, table td { border:1px solid #dee2e6; padding:8px 10px; vertical-align:top; }
table th { background:#f8f9fa; font-weight:600; }
hr { border:none; border-top:1px solid #e5e7eb; margin:2rem 0; }
h3 { font-size:1.3rem; font-weight:700; }
h4 { font-size:1.05rem; font-weight:700; padding-top:1rem; margin-bottom:0.4rem; }
p { margin:0.5rem 0 0.5rem 1rem; }
ul, ol { margin:0.5rem 0 0.5rem 2rem; }
li { margin:3px 0; }
.badge-new { display:inline-block; background:#dc2626; color:#fff; font-size:0.68rem; font-weight:700; padding:1px 5px; border-radius:3px; vertical-align:middle; margin-right:4px; }
/* Sidebar */
#docs-sidebar ul { list-style:none; padding:0; margin:0; }
#docs-sidebar > ul > li { margin:4px 0; }
#docs-sidebar a { color:#333; text-decoration:none; font-size:0.875rem; display:block; padding:3px 0; }
#docs-sidebar a:hover { color:#16a34a; }
#docs-sidebar .sub-nav { padding-left:14px; margin-top:3px; display:none; }
#docs-sidebar .sub-nav.open { display:block; }
#docs-sidebar .sub-nav li { margin:2px 0; }
#docs-sidebar .dropdown-toggle { cursor:pointer; display:flex; align-items:center; justify-content:space-between; }
#docs-sidebar .dropdown-toggle .arrow { font-size:0.65rem; transition:transform 0.2s; display:inline-block; margin-left:4px; }
#docs-sidebar .dropdown-toggle.open .arrow { transform:rotate(90deg); }
</style>
@endpush

<section style="background:#fff; min-height:100vh; padding:2rem 0">
<div style="max-width:1140px;margin:0 auto;padding:0 1.5rem">
<div style="display:flex;gap:0;align-items:stretch">

    {{-- ===== SIDEBAR ===== --}}
    <div id="sidebar-sticky" style="flex:0 0 220px;min-width:220px;position:sticky;top:0;height:100vh;overflow-y:auto;background:#f8f9fa;border-right:1px solid #e9ecef;padding:1.5rem 1rem;align-self:flex-start">
            <nav id="docs-sidebar">
                <ul>
                    <li><a href="#intro">Introduction</a></li>
                    <li><a href="#types">Types</a></li>
                    <li>
                        <span class="dropdown-toggle" id="solving-toggle">
                            <a href="#solving_captchas" onclick="toggleSolving(event)">Solving Captchas</a>
                            <span class="arrow">&#9658;</span>
                        </span>
                        <ul class="sub-nav" id="solving-submenu">
                            <li><a href="#solving_normal_captcha">Normal Captcha</a></li>
                            <li><a href="#solving_recaptchav2">reCAPTCHA V2</a></li>
                            <li><a href="#callback">reCAPTCHA Callback</a></li>
                            <li><a href="#invisible">Invisible reCAPTCHA V2</a></li>
                            <li><a href="#solving_recaptchav3">reCAPTCHA V3</a></li>
                        </ul>
                    </li>
                    <li><a href="#language">Language support</a></li>
                    <li><a href="#proxies">Using proxies</a></li>
                    <li><a href="#cookies">Cookies</a></li>
                    <li>
                        <span class="dropdown-toggle" id="error-toggle">
                            <a href="#error_handling" onclick="toggleError(event)">Error Handling</a>
                            <span class="arrow">&#9658;</span>
                        </span>
                        <ul class="sub-nav" id="error-submenu">
                            <li><a href="#in_errors">List of in.php errors</a></li>
                            <li><a href="#res_errors">List of res.php errors</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
    </div>

    {{-- ===== CONTENT ===== --}}
    <div style="flex:1;min-width:0">
    <div style="background:#fff;padding:2rem">

        <h3><span style="color:#16a34a">10</span>Captcha API</h3>
        <p>10captcha is an AI-powered image and CAPTCHA recognition service. 10captcha's main purpose is solving your CAPTCHAs in a quick and cost effective way by using AI "Artificial Intelligence".</p>

        <hr>

        {{-- Introduction --}}
        <h3 id="intro">Introduction</h3>
        <p>We provide an API that allows you to automate the process and integrate your software with our service.</p>
        <p>There are few simple steps to solve your captcha or recognize the image:</p>
        <ul>
            <li>1. Send your image or captcha to our server.</li>
            <li>2. Get the ID of your task.</li>
            <li>3. Start a cycle that checks if your task is completed.</li>
            <li>4. Get the result.</li>
        </ul>

        <hr>

        {{-- Types --}}
        <h3 id="types">Captcha types</h3>
        <table>
            <thead>
                <tr><th>Type of captcha/method</th><th>Description</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="#solving_normal_captcha" style="color:#16a34a">Normal Captcha</a></td>
                    <td>27,500+ image captchas including, Solve Media, Google captcha, reCAPTCHA v1, Facebook captcha, etc.</td>
                </tr>
                <tr>
                    <td><a href="#solving_recaptchav2" style="color:#16a34a">reCAPTCHA V2</a></td>
                    <td>Google reCaptcha V2</td>
                </tr>
                <tr>
                    <td><a href="#solving_recaptchav3" style="color:#16a34a">reCAPTCHA V3</a></td>
                    <td>Google reCaptcha V3</td>
                </tr>
            </tbody>
        </table>

        <hr>

        {{-- Solving Captchas --}}
        <h3 id="solving_captchas">Solving Captchas</h3>
        <p>Our API is based on HTTP requests and supports both HTTP and HTTPS protocols.</p>
        <p>API endpoints:</p>
        <ul>
            <li><code>https://ocr.10captcha.com/in.php</code> is used to submit a captcha</li>
            <li><code>https://ocr.10captcha.com/res.php</code> is used to get the captcha solution</li>
        </ul>
        <p><span class="badge-new">NEW</span> For image captcha, now you can use <code>https://ocr.10captcha.com/solve.php</code> instead of <code>in.php</code> to directly get the results.</p>
        <p>The process of solving captchas with 10captcha is really easy and it's mostly the same for all types of captchas:</p>
        <ol>
            <li>
                <p>Get your API key from <a href="{{ route('api.page') }}" style="color:#16a34a">your API key</a>. Each user is given a unique authentication token, we call it <em>API key</em>. It's a 32-characters string that looks like: <code>347bc2896fc1812d3de5ab56a0bf4ea7</code> This key will be used for all your requests to our server.</p>
            </li>
            <li>
                <p>Submit a <em>HTTP POST</em> request to our API URL: <code>https://ocr.10captcha.com/in.php</code> with parameters corresponding to the type of your captcha. Server will return captcha ID or an <a href="#error_handling" style="color:#16a34a">error code</a> if something went wrong.</p>
            </li>
            <li>
                <p>Make a timeout: 20 seconds for reCAPTCHA, 5 seconds for other types of captchas.</p>
            </li>
            <li>
                <p>Submit a <em>HTTP GET</em> request to our API URL: <code>https://ocr.10captcha.com/res.php</code> to get the result. If captcha is already solved server will return the answer in format corresponding to the type of your captcha. By default answers are returned as plain text like: <em>OK|Your answer</em>. But answer can also be returned as JSON <em>{"status":1,"request":"TEXT"}</em> if <em>json</em> parameter is used. If captcha is not solved yet server will return <em>CAPCHA_NOT_READY</em> result. Repeat your request in 5 seconds. If something went wrong server will return an <a href="#error_handling" style="color:#16a34a">error code</a>.</p>
            </li>
        </ol>

        {{-- Normal Captcha --}}
        <h4 id="solving_normal_captcha">Normal Captcha</h4>
        <p>Normal Captcha is an image that contains distorted but human-readable text. To solve the captcha user have to type the text from the image.</p>
        <p>To solve the captcha with our service you have to submit the image with <em>HTTP POST</em> request to our API URL: <code>https://ocr.10captcha.com/in.php</code> Server accepts images in <em>multipart</em> or <em>base64</em> format.</p>
        <p><span class="badge-new">NEW</span> For image captcha, now you can use <code>https://ocr.10captcha.com/solve.php</code> instead of <code>in.php</code> to directly get the results.</p>

        <p><strong>Multipart sample form</strong></p>
        <pre><code>&lt;form method="post" action="https://ocr.10captcha.com/in.php" enctype="multipart/form-data"&gt;
  &lt;input type="hidden" name="method" value="post"&gt;
  Your key: &lt;input type="text" name="key" value="YOUR_APIKEY"&gt;
  The CAPTCHA file: &lt;input type="file" name="file"&gt;
  &lt;input type="submit" value="Upload and get the ID"&gt;
&lt;/form&gt;</code></pre>
        <p><em>YOUR_APIKEY</em> is <a href="{{ route('api.page') }}" style="color:#16a34a">Your API key</a>.</p>

        <p><strong>Base64 sample form</strong></p>
        <pre><code>&lt;form method="post" action="https://ocr.10captcha.com/in.php"&gt;
  &lt;input type="hidden" name="method" value="base64"&gt;
  Your key: &lt;input type="text" name="key" value="YOUR_APIKEY"&gt;
  The CAPTCHA file body in base64 format:
  &lt;textarea name="body"&gt;BASE64_FILE&lt;/textarea&gt;
  &lt;input type="submit" value="Upload and get the ID"&gt;
&lt;/form&gt;</code></pre>
        <p><em>YOUR_APIKEY</em> is here <a href="{{ route('api.page') }}" style="color:#16a34a">your API key</a>.</p>
        <p><em>BASE64_FILE</em> is base64-encoded image body.</p>

        <p>You can provide additional parameters with your request to define what kind of captcha you're sending and to help OCR servers to solve your captcha correctly. You can find the full list of parameters in the <a href="#normal_post" style="color:#16a34a">table below.</a></p>
        <p>If everything is fine server will return the ID of your captcha as plain text, like: <em>OK|123456789</em> or as JSON <em>{"status":1,"request":"123456789"}</em> if json parameter was used.</p>
        <p>If something went wrong server will return an error. See <a href="#error_handling" style="color:#16a34a">Error Handling</a> chapter for the list of errors.</p>
        <p>Make a 5 seconds timeout and submit a <em>HTTP GET</em> request to our API URL: <code>https://ocr.10captcha.com/res.php</code> providing the captcha ID. The list of parameters is in the <a href="#normal_get" style="color:#16a34a">table below</a>.</p>
        <p>If everything is fine and your captcha is solved server will return the answer as plain text, like: <em>OK|TEXT</em> or as JSON <em>{"status":1,"request":"TEXT"}</em> if <em>json</em> parameter was used.</p>
        <p>Otherwise server will return <em>CAPCHA_NOT_READY</em> that means that your captcha is not solved yet. Just repeat your request in 5 seconds.</p>
        <p>If something went wrong server will return an error. See <a href="#error_handling" style="color:#16a34a">Error Handling</a> chapter for the list of errors.</p>

        <p id="normal_post"><strong>List of <em>POST</em> request parameters for https://ocr.10captcha.com/in.php</strong></p>
        <table>
            <tbody>
                <tr><th>POST parameter</th><th>Type</th><th>Required</th><th>Description</th></tr>
                <tr><td>key</td><td>String</td><td>Yes</td><td><a href="{{ route('api.page') }}" style="color:#16a34a">your API key</a></td></tr>
                <tr><td>method</td><td>String</td><td>Yes</td><td>post - defines that you're sending an image with multipart form<br>base64 - defines that you're sending a base64 encoded image</td></tr>
                <tr><td>file</td><td>File</td><td>Yes*</td><td>Captcha image file.<br>* - required if you submit image as a file (method=post)</td></tr>
                <tr><td>body</td><td>String</td><td>Yes*</td><td>Base64-encoded captcha image<br>* - required if you submit image as Base64-encoded string (method=base64)</td></tr>
                <tr><td>module</td><td>String</td><td>No</td><td><span class="badge-new">NEW</span> Choose from custom image modules for perfect results.<br>List of available modules: common-1, common-2, common-3, collection-1, collection-2, collection-3, collection-4, collection-5, ...</td></tr>
                <tr><td>phrase</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - captcha contains one word<br>1 - captcha contains two or more words</td></tr>
                <tr><td>regsense</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - captcha is not case sensitive<br>1 - captcha is case sensitive</td></tr>
                <tr><td>numeric</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - not specified<br>1 - captcha contains only numbers<br>2 - captcha contains only letters<br>3 - captcha contains only numbers OR only letters<br>4 - captcha contains both numbers AND letters</td></tr>
                <tr><td>calc</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - not specified<br>1 - captcha requires calculation (e.g. type the result 4 + 8 = )</td></tr>
                <tr><td>min_len</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - not specified<br>1..20 - minimal number of symbols in captcha</td></tr>
                <tr><td>max_len</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - not specified<br>1..20 - maximal number of symbols in captcha</td></tr>
                <tr><td>language</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - not specified<br>1 - Cyrillic captcha<br>2 - Latin captcha</td></tr>
                <tr><td>lang</td><td>String</td><td>No</td><td>Language code. See the <a href="#language" style="color:#16a34a">list of supported languages</a>.</td></tr>
                <tr><td>json</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - server will send the response as plain text<br>1 - tells the server to send the response as JSON</td></tr>
            </tbody>
        </table>

        <p id="normal_get"><strong>List of <em>GET</em> request parameters for https://ocr.10captcha.com/res.php</strong></p>
        <table>
            <tbody>
                <tr><th>GET parameter</th><th>Type</th><th>Required</th><th>Description</th></tr>
                <tr><td>key</td><td>String</td><td>Yes</td><td><a href="{{ route('api.page') }}" style="color:#16a34a">your API key</a></td></tr>
                <tr><td>action</td><td>String</td><td>Yes</td><td>get - get the answer for your captcha</td></tr>
                <tr><td>id</td><td>Integer</td><td>Yes</td><td>ID of captcha returned by in.php.</td></tr>
                <tr><td>json</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - server will send the response as plain text<br>1 - tells the server to send the response as JSON</td></tr>
                <tr><td>header_acao</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - disabled<br>1 - enabled. If enabled res.php will include Access-Control-Allow-Origin:* header in the response.</td></tr>
            </tbody>
        </table>

        <p><strong>Request URL example:</strong></p>
        <pre><code>https://ocr.10captcha.com/res.php?key=347bc2896fc1812d3de5ab56a0bf4ea7&amp;action=get&amp;id=123456789</code></pre>

        <hr>

        {{-- reCAPTCHA V2 --}}
        <h4 id="solving_recaptchav2">reCAPTCHA V2</h4>
        <p>reCAPTCHA V2 also known as "I'm not a robot" reCAPTCHA is a very popular type of captcha.</p>
        <p>Solving reCAPTCHA V2 with our method is pretty simple:</p>
        <ol>
            <li>
                <p>Look at the element's code at the page where you found reCAPTCHA.</p>
            </li>
            <li>
                <p>Find a link that begins with <code>www.google.com/recaptcha/api2/anchor</code> or find <code>data-sitekey</code> parameter.</p>
            </li>
            <li>
                <p>Copy the value of <em>k</em> parameter of the link (or value of <em>data-sitekey</em> parameter).</p>
            </li>
            <li>
                <p>Submit a <em>HTTP GET</em> or <em>POST</em> request to our API URL: <code>https://ocr.10captcha.com/in.php</code> with method set to <em>userrecaptcha</em> and the value found on previous step as value for <em>googlekey</em> and full page URL as value for <em>pageurl</em>. You can find the full list of parameters in the table below.</p>
                <p><strong>Request URL example:</strong></p>
                <pre><code>https://ocr.10captcha.com/in.php?key=347bc2896fc1812d3de5ab56a0bf4ea7&amp;method=userrecaptcha&amp;googlekey=6Le-wvkSVVABCPBMRTvw0Q4Muexq1bi0DJwx_mJ-&amp;pageurl=https://mysite.com/page/with/recaptcha</code></pre>
            </li>
            <li>
                <p>If everything is fine server will return the ID of your captcha as plain text, like: <em>OK|123456789</em> or as JSON <em>{"status":1,"request":"123456789"}</em> if json parameter was used. Otherwise server will return an error code.</p>
            </li>
            <li>
                <p>Make a 15-20 seconds timeout then submit a <em>HTTP GET</em> request to our API URL: <code>https://ocr.10captcha.com/res.php</code> to get the result. The full list of parameters is in the table below.</p>
                <p>If captcha is already solved server will return the answer token.</p>
            </li>
            <li>
                <p>Locate the element with id <em>g-recaptcha-response</em> and make it visible. As an alternative you can use javascript:</p>
                <pre><code>document.getElementById("g-recaptcha-response").innerHTML="TOKEN_FROM_10captcha";</code></pre>
            </li>
            <li>
                <p>Paste the answer token to the g-recaptcha-response field and submit the form.</p>
            </li>
            <li>
                <p>Congratulations, you've passed the recaptcha!</p>
            </li>
        </ol>

        <p id="recaptchav2_post" style="margin-top:1.5rem"><strong>List of GET/POST request parameters for https://ocr.10captcha.com/in.php</strong></p>
        <table>
            <tbody>
                <tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr>
                <tr><td>key</td><td>String</td><td>Yes</td><td><a href="{{ route('api.page') }}" style="color:#16a34a">your API key</a></td></tr>
                <tr><td>method</td><td>String</td><td>Yes</td><td>userrecaptcha - defines that you're sending a reCAPTCHA V2 with new method</td></tr>
                <tr><td>googlekey</td><td>String</td><td>Yes</td><td>Value of <em>k</em> or <em>data-sitekey</em> parameter you found on page</td></tr>
                <tr><td>pageurl</td><td>String</td><td>Yes</td><td>Full URL of the page where you see the reCAPTCHA</td></tr>
                <tr><td>domain</td><td>String<br>Default: google.com</td><td>No</td><td>Domain used to load the captcha: google.com or recaptcha.net</td></tr>
                <tr><td>invisible</td><td>Integer<br>Default: 0</td><td>No</td><td>1 - means that reCAPTCHA is invisible.<br>0 - normal reCAPTCHA.</td></tr>
                <tr><td>data-s</td><td>String</td><td>No</td><td>Value of <em>data-s</em> parameter you found on page. Currently applicable for Google Search and other Google services.</td></tr>
                <tr><td>cookies</td><td>String</td><td>No</td><td>Your cookies that will be passed to our OCR server. Format: KEY:Value, separator: semicolon.</td></tr>
                <tr><td>userAgent</td><td>String</td><td>No</td><td>Your userAgent that will be passed to our OCR server and used to solve the captcha.</td></tr>
                <tr><td>header_acao</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - disabled<br>1 - enabled. If enabled in.php will include Access-Control-Allow-Origin:* header in the response.</td></tr>
                <tr><td>json</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - server will send the response as plain text<br>1 - tells the server to send the response as JSON</td></tr>
                <tr><td>proxy</td><td>String</td><td>No</td><td>Format: login:password@123.123.123.123:3128<br>You can find more info about proxies <a href="#proxies" style="color:#16a34a">here</a>.</td></tr>
                <tr><td>proxytype</td><td>String</td><td>No</td><td>Type of your proxy: HTTP, HTTPS, SOCKS4, SOCKS5.</td></tr>
            </tbody>
        </table>

        <p id="recaptchav2_get" style="margin-top:1.5rem"><strong>List of GET request parameters for https://ocr.10captcha.com/res.php</strong></p>
        <table>
            <tbody>
                <tr><th>GET parameter</th><th>Type</th><th>Required</th><th>Description</th></tr>
                <tr><td>key</td><td>String</td><td>Yes</td><td><a href="{{ route('api.page') }}" style="color:#16a34a">your API key</a></td></tr>
                <tr><td>action</td><td>String</td><td>Yes</td><td>get - get the answer for your captcha</td></tr>
                <tr><td>id</td><td>Integer</td><td>Yes</td><td>ID of captcha returned by in.php.</td></tr>
                <tr><td>json</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - server will send the response as plain text<br>1 - tells the server to send the response as JSON</td></tr>
            </tbody>
        </table>

        <hr>

        {{-- reCAPTCHA Callback --}}
        <h4 id="callback">reCAPTCHA Callback</h4>
        <p>Sometimes there's no submit button and a callback function is used instead. The function is called when reCAPTCHA is solved.</p>
        <p>Callback function is usually defined in <code>data-callback</code> parameter of reCAPTCHA, for example:</p>
        <pre><code>data-callback="myCallbackFunction"</code></pre>
        <p>Or sometimes it's defined as <code>callback</code> parameter of <code>grecaptcha.render</code> function, for example:</p>
        <pre><code>grecaptcha.render('example', {
    'sitekey' : 'someSitekey',
    'callback' : myCallbackFunction,
    'theme' : 'dark'
});</code></pre>
        <p>Also there's another way to find the callback function - open javascript console of your browser and explore reCAPTCHA configuration object:</p>
        <pre><code>___grecaptcha_cfg.clients[0].aa.l.callback</code></pre>
        <p>Note that <em>aa.l</em> may change and there can be multiple clients so you have to check clients[1], clients[2] too.</p>
        <p>Finally all you have to do is to call that function:</p>
        <pre><code>myCallbackFunction();</code></pre>
        <p>Or even this way:</p>
        <pre><code>___grecaptcha_cfg.clients[0].aa.l.callback();</code></pre>
        <p>Sometimes it is required to provide an argument and in most cases you should put the token there. For example:</p>
        <pre><code>myCallbackFunction('TOKEN');</code></pre>

        <hr>

        {{-- Invisible reCAPTCHA V2 --}}
        <h4 id="invisible">Invisible reCAPTCHA V2</h4>
        <p>reCAPTCHA V2 also has an invisible version. We added parameter <code>invisible=1</code> that should be used for invisible reCAPTCHA.</p>
        <p>Invisible reCAPTCHA is located on a DIV layer positioned -10,000px from top that makes it invisible for user.</p>
        <p>If you are not sure — there are few ways to determine that reCAPTCHA is in invisible mode:</p>
        <ul>
            <li>You don't see "I'm not a robot" checkbox on the page but getting recaptcha challenge making some actions there</li>
            <li>reCAPTCHA's iframe link contains parameter <code>size=invisible</code></li>
            <li>reCAPTCHA's configuration object contains parameter size that is set to invisible, for example <code>___grecaptcha_cfg.clients[0].aa.l.size</code> is equal to <code>invisible</code></li>
        </ul>

        <p><strong>How to bypass invisible reCAPTCHA in browser?</strong></p>
        <p><strong>Method 1:</strong> using javascript:</p>
        <ol>
            <li>
                <p>Change the value of g-recaptcha-response element to the token you received from our server:</p>
                <pre><code>document.getElementById("g-recaptcha-response").innerHTML="TOKEN_FROM_10captcha";</code></pre>
            </li>
            <li>
                <p>Execute the action that needs to be performed on the page after solving reCAPTCHA. Usually there's a form that should be submitted:</p>
                <pre><code>document.getElementById("recaptcha-demo-form").submit(); //by id
document.getElementsByName("myFormName")[0].submit(); //by element name
document.getElementsByClassName("example").submit(); //by class name</code></pre>
                <p>Or call the callback function:</p>
                <pre><code>myCallbackFunction();</code></pre>
            </li>
            <li>
                <p>Voila! You've done that with just 2 strings of code.</p>
            </li>
        </ol>

        <p><strong>Method 2:</strong> changing HTML:</p>
        <ol>
            <li>
                <p>Cut the div containing reCAPTCHA from page body and the whole reCAPTCHA block.</p>
            </li>
            <li>
                <p>Put the following code instead of the block you've just cut:</p>
                <pre><code>&lt;input type="submit"&gt;
&lt;textarea name="g-recaptcha-response"&gt;%g-recaptcha-response%&lt;/textarea&gt;</code></pre>
                <p>Where <em>%g-recaptcha-response%</em> - is an answer token you've got from our service.</p>
            </li>
            <li>
                <p>You will see "Submit query" button. Press the button to submit the form with g-recaptcha-response and all other form data to the website.</p>
            </li>
        </ol>

        <p id="invisible_post" style="margin-top:1.5rem"><strong>List of GET/POST request parameters for https://ocr.10captcha.com/in.php</strong></p>
        <table>
            <tbody>
                <tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr>
                <tr><td>key</td><td>String</td><td>Yes</td><td><a href="{{ route('api.page') }}" style="color:#16a34a">your API key</a></td></tr>
                <tr><td>method</td><td>String</td><td>Yes</td><td>userrecaptcha - defines that you're sending a reCAPTCHA V2 with new method</td></tr>
                <tr><td>googlekey</td><td>String</td><td>Yes</td><td>Value of <em>k</em> or <em>data-sitekey</em> parameter you found on page</td></tr>
                <tr><td>pageurl</td><td>String</td><td>Yes</td><td>Full URL of the page where you see the reCAPTCHA</td></tr>
                <tr><td>domain</td><td>String<br>Default: google.com</td><td>No</td><td>Domain used to load the captcha: google.com or recaptcha.net</td></tr>
                <tr><td>invisible</td><td>Integer<br>Default: 0</td><td>No</td><td>1 - means that reCAPTCHA is invisible.<br>0 - normal reCAPTCHA.</td></tr>
                <tr><td>data-s</td><td>String</td><td>No</td><td>Value of <em>data-s</em> parameter you found on page.</td></tr>
                <tr><td>cookies</td><td>String</td><td>No</td><td>Your cookies in format KEY:Value;KEY2:Value2;</td></tr>
                <tr><td>userAgent</td><td>String</td><td>No</td><td>Your userAgent that will be passed to our OCR server.</td></tr>
                <tr><td>header_acao</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - disabled<br>1 - enabled. If enabled in.php will include Access-Control-Allow-Origin:* header.</td></tr>
                <tr><td>json</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - server will send the response as plain text<br>1 - tells the server to send the response as JSON</td></tr>
                <tr><td>proxy</td><td>String</td><td>No</td><td>Format: login:password@123.123.123.123:3128</td></tr>
                <tr><td>proxytype</td><td>String</td><td>No</td><td>Type of your proxy: HTTP, HTTPS, SOCKS4, SOCKS5.</td></tr>
            </tbody>
        </table>

        <p id="invisible_get" style="margin-top:1.5rem"><strong>List of GET request parameters for https://ocr.10captcha.com/res.php</strong></p>
        <table>
            <tbody>
                <tr><th>GET parameter</th><th>Type</th><th>Required</th><th>Description</th></tr>
                <tr><td>key</td><td>String</td><td>Yes</td><td><a href="{{ route('api.page') }}" style="color:#16a34a">your API key</a></td></tr>
                <tr><td>action</td><td>String</td><td>Yes</td><td>get - get the answer for your captcha</td></tr>
                <tr><td>id</td><td>Integer</td><td>Yes</td><td>ID of captcha returned by in.php.</td></tr>
                <tr><td>json</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - server will send the response as plain text<br>1 - tells the server to send the response as JSON</td></tr>
            </tbody>
        </table>

        <p><strong>Request URL example:</strong></p>
        <pre><code>https://ocr.10captcha.com/res.php?key=347bc2896fc1812d3de5ab56a0bf4ea7&amp;action=get&amp;id=123456789</code></pre>

        <hr>

        {{-- reCAPTCHA V3 --}}
        <h4 id="solving_recaptchav3">reCAPTCHA V3</h4>
        <p>reCAPTCHA V3 is the newest type of captcha from Google. It has no challenge so there is no need for user interaction. Instead it uses a "humanity" rating - score.</p>
        <p>reCAPTCHA V3 technically is quite similar to reCAPTCHA V2: customer receives a token from the API that is used to bypass the captcha on the target website.</p>

        <ol>
            <li>
                <p>Find the value of <em>data-sitekey</em> parameter in the source code of the page.</p>
            </li>
            <li>
                <p>Submit a <em>HTTP GET</em> request to our API URL with method set to <em>userrecaptcha</em>, version set to <em>v3</em>, and provide the action and min_score parameters.</p>
                <p><strong>Request URL example:</strong></p>
                <pre><code>https://ocr.10captcha.com/in.php?key=347bc2896fc1812d3de5ab56a0bf4ea7&amp;method=userrecaptcha&amp;version=v3&amp;action=verify&amp;min_score=0.3&amp;googlekey=6LfZil0UAAAAAAdm1Dpzsw9q0F11-bmervx9g5fE&amp;pageurl=https://mysite.com/page/</code></pre>
            </li>
            <li>
                <p>If everything is fine server will return the ID of your captcha: <em>OK|2122988149</em></p>
            </li>
            <li>
                <p>Wait 15-20 seconds and poll for the result:</p>
                <pre><code>https://ocr.10captcha.com/res.php?key=347bc2896fc1812d3de5ab56a0bf4ea7&amp;action=get&amp;id=2122988149</code></pre>
            </li>
            <li>
                <p>Use the token returned to bypass reCAPTCHA V3 on the target page.</p>
            </li>
        </ol>

        <p id="recaptchav3_post" style="margin-top:1.5rem"><strong>List of GET/POST request parameters for https://ocr.10captcha.com/in.php</strong></p>
        <table>
            <tbody>
                <tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr>
                <tr><td>key</td><td>String</td><td>Yes</td><td><a href="{{ route('api.page') }}" style="color:#16a34a">your API key</a></td></tr>
                <tr><td>method</td><td>String</td><td>Yes</td><td>userrecaptcha - defines that you're sending a reCAPTCHA</td></tr>
                <tr><td>version</td><td>String</td><td>Yes</td><td>v3 - defines that you're sending reCAPTCHA V3</td></tr>
                <tr><td>googlekey</td><td>String</td><td>Yes</td><td>Value of <em>data-sitekey</em> parameter you found on page</td></tr>
                <tr><td>pageurl</td><td>String</td><td>Yes</td><td>Full URL of the page where you bypass the captcha</td></tr>
                <tr><td>action</td><td>String</td><td>No</td><td>Value of action parameter you found on page</td></tr>
                <tr><td>min_score</td><td>Float<br>Default: 0.3</td><td>No</td><td>The minimum score needed for the captcha resolution. Currently it can be 0.3, 0.7 or 0.9</td></tr>
                <tr><td>json</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - server will send the response as plain text<br>1 - tells the server to send the response as JSON</td></tr>
                <tr><td>soft_id</td><td>Integer</td><td>No</td><td>ID of the software developer.</td></tr>
            </tbody>
        </table>

        <p id="recaptchav3_get" style="margin-top:1.5rem"><strong>List of GET request parameters for https://ocr.10captcha.com/res.php</strong></p>
        <table>
            <tbody>
                <tr><th>GET parameter</th><th>Type</th><th>Required</th><th>Description</th></tr>
                <tr><td>key</td><td>String</td><td>Yes</td><td><a href="{{ route('api.page') }}" style="color:#16a34a">your API key</a></td></tr>
                <tr><td>action</td><td>String</td><td>Yes</td><td>get - get the answer for your captcha</td></tr>
                <tr><td>id</td><td>Integer</td><td>Yes</td><td>ID of captcha returned by in.php</td></tr>
                <tr><td>json</td><td>Integer<br>Default: 0</td><td>No</td><td>0 - server will send the response as plain text<br>1 - tells the server to send the response as JSON</td></tr>
            </tbody>
        </table>

        <hr>

        {{-- Language support --}}
        <h3 id="language">Language support</h3>
        <p>For Normal Captcha you can specify the language using the <code>lang</code> parameter. Below is the list of supported language codes:</p>
        <table>
            <tbody>
                <tr><th>Language</th><th>Code</th><th>Language</th><th>Code</th></tr>
                <tr><td>Arabic</td><td>ar</td><td>Korean</td><td>ko</td></tr>
                <tr><td>Bulgarian</td><td>bg</td><td>Latvian</td><td>lv</td></tr>
                <tr><td>Chinese (Simplified)</td><td>zh</td><td>Lithuanian</td><td>lt</td></tr>
                <tr><td>Croatian</td><td>hr</td><td>Norwegian</td><td>no</td></tr>
                <tr><td>Czech</td><td>cs</td><td>Polish</td><td>pl</td></tr>
                <tr><td>Danish</td><td>da</td><td>Portuguese</td><td>pt</td></tr>
                <tr><td>Dutch</td><td>nl</td><td>Romanian</td><td>ro</td></tr>
                <tr><td>English</td><td>en</td><td>Russian</td><td>ru</td></tr>
                <tr><td>Estonian</td><td>et</td><td>Slovak</td><td>sk</td></tr>
                <tr><td>Finnish</td><td>fi</td><td>Slovenian</td><td>sl</td></tr>
                <tr><td>French</td><td>fr</td><td>Spanish</td><td>es</td></tr>
                <tr><td>German</td><td>de</td><td>Swedish</td><td>sv</td></tr>
                <tr><td>Greek</td><td>el</td><td>Thai</td><td>th</td></tr>
                <tr><td>Hebrew</td><td>he</td><td>Turkish</td><td>tr</td></tr>
                <tr><td>Hungarian</td><td>hu</td><td>Ukrainian</td><td>uk</td></tr>
                <tr><td>Indonesian</td><td>id</td><td>Vietnamese</td><td>vi</td></tr>
                <tr><td>Italian</td><td>it</td><td></td><td></td></tr>
                <tr><td>Japanese</td><td>ja</td><td></td><td></td></tr>
            </tbody>
        </table>

        <hr>

        {{-- Proxies --}}
        <h3 id="proxies">Using proxies</h3>
        <p>Please note that we disable this option by default to get better results, please contact support if you know you want to open it for your account.</p>
        <p>Proxies can be used to solve most types of javascript-based captchas:</p>
        <ul>
            <li>reCAPTCHA V2</li>
            <li>reCAPTCHA V3</li>
        </ul>
        <p>Proxy allows to solve the captcha from the same IP address as you load the page. Using proxies is not obligatory in most cases. But for some kind of protection you should use it. For example: Cloudflare and Datadome protection pages require IP matching.</p>
        <p>We support the following proxy types: SOCKS4, SOCKS5, HTTP, HTTPS with authentication by IP address or login and password.</p>
        <p>If your proxy uses login/password authentication you have to include your credentials in proxy parameter.</p>

        <h4>POST parameters for proxies</h4>
        <table>
            <tbody>
                <tr><th>POST parameter</th><th>Type</th><th>Required</th><th>Description</th></tr>
                <tr>
                    <td>proxy</td><td>String</td><td>No</td>
                    <td>
                        Format for IP authentication:<br>
                        <code>IP_address:PORT</code><br>
                        Example: <code>proxy=123.123.123.123:3128</code><br><br>
                        Format for login/password authentication:<br>
                        <code>login:password@IP_address:PORT</code><br>
                        Example: <code>proxy=proxyuser:strongPassword@123.123.123.123:3128</code>
                    </td>
                </tr>
                <tr>
                    <td>proxytype</td><td>String</td><td>No</td>
                    <td>Type of your proxy: HTTP, HTTPS, SOCKS4, SOCKS5.<br>Example: <code>proxytype=SOCKS4</code></td>
                </tr>
            </tbody>
        </table>

        <hr>

        {{-- Cookies --}}
        <h3 id="cookies">Cookies</h3>
        <p>Our API provides extended Cookies support for reCAPTCHA V2.</p>
        <p>You can provide your cookies using the format below as the value of <code>json_cookies</code> parameter. We will set the cookies on our OCR server's browser.</p>
        <p>After the captcha was solved successfully, we will return all the cookies set for domains: <code>google.com</code> and the domain of your target website from <code>pageurl</code> parameter value.</p>
        <p>You should use <code>json=1</code> parameter in your request to res.php endpoint to get the cookies.</p>

        <h4>Cookies format:</h4>
        <pre><code>{
    "json_cookies": [
        {
            "name": "my-cookie-name-1",
            "value": "my-cookie-val-1",
            "domain": "example.com",
            "hostOnly": true,
            "path": "/",
            "secure": true,
            "httpOnly": false,
            "session": false,
            "expirationDate": 1665434653,
            "sameSite": "strict"
        },
        {
            "name": "my-cookie-name-2",
            "value": "my-cookie-val-2",
            "domain": ".google.com",
            "hostOnly": false,
            "path": "/",
            "secure": true,
            "httpOnly": false,
            "session": false,
            "expirationDate": 1668015805,
            "sameSite": "no_restriction"
        }
    ]
}</code></pre>

        <p>The following properties are required for each cookie:</p>
        <ul>
            <li><code>domain</code> (String) - the domain for cookie</li>
            <li><code>name</code> (String) - the cookie name</li>
            <li><code>value</code> (String) - the cookie value</li>
            <li><code>secure</code> (Boolean) - should we set secure attribute?</li>
        </ul>

        <hr>

        {{-- Error Handling --}}
        <h3 id="error_handling">Error Handling</h3>
        <p>It's very important to use proper error handling in your code to avoid suspension of your account and service interruption.</p>
        <p>Normally if something is wrong with your request server will return an error. Below you can find tables with lists of errors with descriptions:</p>
        <ul>
            <li>errors returned by <code>https://ocr.10captcha.com/in.php</code></li>
            <li>errors returned by <code>https://ocr.10captcha.com/res.php</code></li>
        </ul>
        <p>Errors can be returned as plain text or as JSON if you provided <code>json=1</code> parameter.</p>
        <p>In very rare cases server can return HTML page with error text like 500 or 502 - please keep it in mind and handle such cases correctly. If you received anything that doesn't look like the answer or error code - make a 5 seconds timeout and then retry your request.</p>

        <h4 id="in_errors">List of in.php errors</h4>
        <table>
            <tbody>
                <tr><th>Error code</th><th>Description</th><th>Action</th></tr>
                <tr>
                    <td>ERROR_WRONG_USER_KEY</td>
                    <td>You've provided key parameter value in incorrect format, it should contain 32 symbols.</td>
                    <td>Stop sending requests. Check your API key.</td>
                </tr>
                <tr>
                    <td>ERROR_KEY_DOES_NOT_EXIST</td>
                    <td>The key you've provided does not exist.</td>
                    <td>Stop sending requests. Check your API key.</td>
                </tr>
                <tr>
                    <td>ERROR_ZERO_BALANCE</td>
                    <td>You don't have free threads.</td>
                    <td>Send less captchas at a time or upgrade your plan.</td>
                </tr>
                <tr>
                    <td>ERROR_PAGEURL</td>
                    <td>pageurl parameter is missing in your request.</td>
                    <td>Stop sending requests and change your code to provide valid pageurl parameter.</td>
                </tr>
                <tr>
                    <td>ERROR_ZERO_CAPTCHA_FILESIZE</td>
                    <td>Image size is less than 100 bytes.</td>
                    <td>Check the image file.</td>
                </tr>
                <tr>
                    <td>ERROR_TOO_BIG_CAPTCHA_FILESIZE</td>
                    <td>Image size is more than 100 kB.</td>
                    <td>Check the image file.</td>
                </tr>
                <tr>
                    <td>ERROR_WRONG_FILE_EXTENSION</td>
                    <td>Image file has unsupported extension. Accepted extensions: jpg, jpeg, gif, png.</td>
                    <td>Check the image file.</td>
                </tr>
                <tr>
                    <td>ERROR_IMAGE_TYPE_NOT_SUPPORTED</td>
                    <td>Server can't recognize image file type.</td>
                    <td>Check the image file.</td>
                </tr>
                <tr>
                    <td>ERROR_UPLOAD</td>
                    <td>Server can't get file data from your POST-request. That happens if your POST-request is malformed or base64 data is not a valid base64 image.</td>
                    <td>You got to fix your code that makes POST request.</td>
                </tr>
                <tr>
                    <td>IP_BANNED</td>
                    <td>Your IP address is banned due to many frequent attempts to access the server using wrong authorization keys.</td>
                    <td>Ban will be automatically lifted after 5 minutes.</td>
                </tr>
                <tr>
                    <td>ERROR_BAD_TOKEN_OR_PAGEURL</td>
                    <td>You can get this error code when sending reCAPTCHA V2. That happens if your request contains invalid pair of googlekey and pageurl.</td>
                    <td>Explore code of the page carefully to find valid pageurl and sitekey values.</td>
                </tr>
                <tr>
                    <td>ERROR_GOOGLEKEY</td>
                    <td>You can get this error code when sending reCAPTCHA V2. That means that sitekey value provided in your request is incorrect: it's blank or malformed.</td>
                    <td>Check your code that gets the sitekey and makes requests to our API.</td>
                </tr>
                <tr>
                    <td>ERROR_WRONG_GOOGLEKEY</td>
                    <td>googlekey parameter is missing in your request.</td>
                    <td>Check your code that gets the sitekey and makes requests to our API.</td>
                </tr>
                <tr>
                    <td>ERROR_CAPTCHAIMAGE_BLOCKED</td>
                    <td>You've sent an image that is marked in our database as unrecognizable.</td>
                    <td>Try to override website's limitations.</td>
                </tr>
                <tr>
                    <td>ERROR_BAD_PARAMETERS</td>
                    <td>The error code is returned if some required parameters are missing in your request or the values have incorrect format.</td>
                    <td>Check that your request contains all the required parameters and the values are in proper format.</td>
                </tr>
                <tr>
                    <td>ERROR_BAD_PROXY</td>
                    <td>You can get this error code when sending a captcha via proxy server which is marked as BAD by our API.</td>
                    <td>Use a different proxy server in your requests.</td>
                </tr>
                <tr>
                    <td>ERROR_SERVER_ERROR</td>
                    <td>Something went wrong with our server.</td>
                    <td>Try again after 10 seconds.</td>
                </tr>
                <tr>
                    <td>ERROR_INTERNAL_SERVER_ERROR</td>
                    <td>Something went wrong with our captcha processing servers.</td>
                    <td>Try again after 10 seconds.</td>
                </tr>
            </tbody>
        </table>

        <h4 id="res_errors">List of res.php errors</h4>
        <table>
            <tbody>
                <tr><th>Error code</th><th>Description</th><th>Action</th></tr>
                <tr>
                    <td>CAPCHA_NOT_READY</td>
                    <td>Your captcha is not solved yet.</td>
                    <td>Make 5 seconds timeout and repeat your request.</td>
                </tr>
                <tr>
                    <td>ERROR_CAPTCHA_UNSOLVABLE</td>
                    <td>We are unable to solve your captcha - it may not be supported.</td>
                    <td>Check if supported &amp; you can retry to send your captcha.</td>
                </tr>
                <tr>
                    <td>ERROR_WRONG_USER_KEY</td>
                    <td>You've provided key parameter value in incorrect format, it should contain 32 symbols.</td>
                    <td>Stop sending requests. Check your API key.</td>
                </tr>
                <tr>
                    <td>ERROR_KEY_DOES_NOT_EXIST</td>
                    <td>The key you've provided does not exist.</td>
                    <td>Stop sending requests. Check your API key.</td>
                </tr>
                <tr>
                    <td>ERROR_WRONG_ID_FORMAT</td>
                    <td>You've provided captcha ID in wrong format. The ID can contain numbers only.</td>
                    <td>Check the ID of captcha or your code that gets the ID.</td>
                </tr>
                <tr>
                    <td>ERROR_WRONG_CAPTCHA_ID</td>
                    <td>You've provided incorrect captcha ID.</td>
                    <td>Check the ID of captcha or your code that gets the ID.</td>
                </tr>
                <tr>
                    <td>ERROR_EMPTY_ACTION</td>
                    <td>Action parameter is missing or no value is provided for action parameter.</td>
                    <td>Check your request parameters and add the necessary value, e.g. get or getbalance.</td>
                </tr>
                <tr>
                    <td>ERROR_PROXY_CONNECTION_FAILED</td>
                    <td>We were unable to load a captcha through your proxy server.</td>
                    <td>Use a different proxy server in your requests.</td>
                </tr>
                <tr>
                    <td>ERROR_INTERNAL_SERVER_ERROR</td>
                    <td>Something went wrong with our captcha processing servers.</td>
                    <td>Try again after 10 seconds.</td>
                </tr>
            </tbody>
        </table>

    </div>
    </div>

</div>
</div>

</div>
</div>
</section>

@push('scripts')
<script>
function toggleSolving(e) {
    e.preventDefault();
    var submenu = document.getElementById('solving-submenu');
    var toggle = document.getElementById('solving-toggle');
    submenu.classList.toggle('open');
    toggle.classList.toggle('open');
}
function toggleError(e) {
    e.preventDefault();
    var submenu = document.getElementById('error-submenu');
    var toggle = document.getElementById('error-toggle');
    submenu.classList.toggle('open');
    toggle.classList.toggle('open');
}
</script>
@endpush

</x-app-layout>
