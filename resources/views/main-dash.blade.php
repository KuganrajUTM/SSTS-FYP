@extends('layout.main-template')

@section('content')
<div class="glow-tr"></div>
<div class="glow-bl"></div>

<div class="road-strip">
  <div class="road-surface"></div>
  <div class="road-dash"></div>
</div>

<div class="register-wrap">
  <div class="register-card text-center">

    <div class="brand-top justify-content-center">
      <img src="{{ asset('assets/img/photo_2024-10-22_11-35-22-Photoroom.png') }}" alt="SSTS Logo">
      <span class="name">SS<span>TS</span></span>
    </div>

    <h2 class="fade-in">Welcome, <span>{{$userName}}!</span></h2>
    <p class="sub mt-3">"The journey of a thousand miles begins with a single step."</p>

    <div class="divider"></div>

    <p class="status-text">We are glad to have you here. Let's make today productive!</p>

    {{-- Driver: Start/Stop Tracking + SOS --}}
    @if(isset($userRole) && $userRole === 'D')
    <div class="mt-4">
        <button id="trackingBtn" class="btn btn-track" onclick="toggleTracking()">
            <i class="fas fa-map-marker-alt me-2"></i> Start Tracking
        </button>
        <p id="trackingStatus" class="mt-2 text-muted" style="font-size:0.85rem;"></p>
    </div>

    @endif

    {{-- Parent: Live Map --}}
    @if(isset($userRole) && $userRole === 'P' && isset($parentDriverIds) && count($parentDriverIds) > 0)
    <div class="mt-4" style="text-align:left;">
        <p class="text-muted mb-2" style="font-size:0.85rem;"><i class="fas fa-circle text-success me-1" style="font-size:0.6rem;"></i> Live Driver Location</p>

        {{-- Arrival Alert Banner --}}
        <div id="arrivalAlert" style="display:none; background:linear-gradient(135deg,#00b894,#007a63); color:#fff; border-radius:10px; padding:12px 16px; margin-bottom:12px; font-weight:600; font-size:0.95rem; align-items:center; gap:10px; animation: fadeUp 0.4s ease both;">
            <i class="fas fa-check-circle me-2" style="font-size:1.1rem;"></i>
            <span id="arrivalAlertText">Van has arrived at school!</span>
            <button onclick="dismissArrival()" style="background:none;border:none;color:#fff;margin-left:auto;font-size:1.2rem;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <div id="map" style="height:500px; border-radius:12px; border:1.5px solid rgba(0,184,148,0.3); overflow:hidden;"></div>
        <p id="mapStatus" class="mt-2 text-muted" style="font-size:0.8rem;"></p>
    </div>
    @elseif(isset($userRole) && $userRole === 'P')
    <div class="mt-4">
        <p class="text-muted" style="font-size:0.9rem;"><i class="fas fa-info-circle me-1"></i> No driver assigned yet.</p>
    </div>
    @endif

  </div>
</div>

{{-- Driver: Fixed SOS Floating Button + Panel --}}
@if(isset($userRole) && $userRole === 'D')
<button class="btn-sos-fab" id="sosFab" onclick="toggleSosPanel()" title="SOS Emergency">
    <i class="fas fa-exclamation-triangle"></i>
    <span class="sos-fab-label">SOS</span>
</button>

<div id="sosPanel" class="sos-panel-fixed" style="display:none;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.85rem;">
        <div style="font-weight:700; color:#e17055; font-size:0.95rem;">
            <i class="fas fa-microphone me-1"></i> Record Emergency Message
        </div>
        <button onclick="toggleSosPanel()" style="background:none; border:none; color:#b2bec3; font-size:1.1rem; cursor:pointer; line-height:1;">&times;</button>
    </div>

    <div id="sosIdle">
        <button class="btn btn-record-sos" onclick="startSosRecording()">
            <i class="fas fa-microphone me-2"></i> Start Recording
        </button>
    </div>

    <div id="sosRecording" style="display:none;">
        <div style="color:#e17055; font-weight:600; margin-bottom:0.65rem; font-size:0.9rem;">
            <i class="fas fa-circle sos-blink me-1"></i> Recording... <span id="sosTimer">0:00</span>
        </div>
        <button class="btn btn-stop-sos" onclick="stopSosRecording()">
            <i class="fas fa-stop me-2"></i> Stop Recording
        </button>
    </div>

    <div id="sosPreview" style="display:none;">
        <audio id="sosAudio" controls style="width:100%; margin-bottom:0.75rem; border-radius:8px;"></audio>
        <div id="sosTranscriptBox" style="background:#fff; border:1px solid #e9b8af; border-radius:8px; padding:0.6rem 0.75rem; margin-bottom:0.75rem; font-size:0.88rem; color:#636e72; min-height:36px;"></div>
        <div style="display:flex; gap:0.6rem; flex-wrap:wrap;">
            <button class="btn btn-send-sos" onclick="sendSosMessage()">
                <i class="fas fa-paper-plane me-2"></i> Send SOS
            </button>
            <button class="btn btn-rerecord-sos" onclick="rerecordSos()">
                <i class="fas fa-redo me-2"></i> Re-record
            </button>
        </div>
    </div>

    <div id="sosSending" style="display:none; color:#636e72; font-size:0.9rem; padding:0.5rem 0;">
        <i class="fas fa-spinner fa-spin me-2"></i> Sending to parents...
    </div>

    <div id="sosSent" style="display:none; color:#00b894; font-weight:700; font-size:0.9rem; padding:0.5rem 0;">
        <i class="fas fa-check-circle me-2"></i> SOS message sent to parents!
        <div style="margin-top:0.5rem;">
            <button class="btn btn-record-sos" onclick="rerecordSos()" style="font-size:0.8rem; padding:0.4rem 1rem;">
                <i class="fas fa-microphone me-1"></i> Send Another
            </button>
        </div>
    </div>
</div>
@endif

{{-- Driver: Tracking + SOS JS --}}
@if(isset($userRole) && $userRole === 'D')
<script>
    let trackingInterval = null;
    let isTracking = false;

    // ---- SOS ----
    let sosMediaRecorder = null;
    let sosAudioChunks   = [];
    let sosAudioBlob     = null;
    let sosTranscript    = '';
    let sosTimerInterval = null;
    let sosSeconds       = 0;
    let sosRecognition   = null;

    function toggleSosPanel() {
        const p = document.getElementById('sosPanel');
        p.style.display = (p.style.display === 'none' || p.style.display === '') ? 'block' : 'none';
    }

    function startSosRecording() {
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(function(stream) {
                sosAudioChunks = [];
                sosTranscript  = '';

                // Try live speech-to-text (Chrome/Edge only)
                const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                if (SR) {
                    sosRecognition = new SR();
                    sosRecognition.continuous      = true;
                    sosRecognition.interimResults  = false;
                    sosRecognition.onresult = function(e) {
                        for (let i = e.resultIndex; i < e.results.length; i++) {
                            sosTranscript += e.results[i][0].transcript + ' ';
                        }
                    };
                    try { sosRecognition.start(); } catch(err) {}
                }

                const mimeType = MediaRecorder.isTypeSupported('audio/webm') ? 'audio/webm' : 'audio/mp4';
                sosMediaRecorder = new MediaRecorder(stream, { mimeType });
                sosMediaRecorder.ondataavailable = function(e) { sosAudioChunks.push(e.data); };
                sosMediaRecorder.onstop = function() {
                    sosAudioBlob = new Blob(sosAudioChunks, { type: mimeType });
                    document.getElementById('sosAudio').src = URL.createObjectURL(sosAudioBlob);
                    document.getElementById('sosTranscriptBox').textContent =
                        sosTranscript.trim() ? '🗣 ' + sosTranscript.trim() : '(No auto-transcript — audio still saved)';
                    document.getElementById('sosRecording').style.display = 'none';
                    document.getElementById('sosPreview').style.display   = 'block';
                    stream.getTracks().forEach(function(t) { t.stop(); });
                };
                sosMediaRecorder.start();

                sosSeconds = 0;
                sosTimerInterval = setInterval(function() {
                    sosSeconds++;
                    const m = Math.floor(sosSeconds / 60);
                    const s = sosSeconds % 60;
                    document.getElementById('sosTimer').textContent = m + ':' + (s < 10 ? '0' : '') + s;
                }, 1000);

                document.getElementById('sosIdle').style.display      = 'none';
                document.getElementById('sosRecording').style.display = 'block';
            })
            .catch(function() {
                alert('Microphone access denied. Please allow microphone permission and try again.');
            });
    }

    function stopSosRecording() {
        if (sosMediaRecorder && sosMediaRecorder.state !== 'inactive') sosMediaRecorder.stop();
        if (sosRecognition) { try { sosRecognition.stop(); } catch(e) {} }
        clearInterval(sosTimerInterval);
    }

    function rerecordSos() {
        sosAudioBlob = null;
        sosTranscript = '';
        document.getElementById('sosPreview').style.display = 'none';
        document.getElementById('sosSent').style.display    = 'none';
        document.getElementById('sosSending').style.display = 'none';
        document.getElementById('sosIdle').style.display    = 'block';
    }

    function sendSosMessage() {
        if (!sosAudioBlob) return;

        document.getElementById('sosPreview').style.display  = 'none';
        document.getElementById('sosSending').style.display  = 'block';

        const ext = sosAudioBlob.type.includes('mp4') ? 'mp4' : 'webm';
        const formData = new FormData();
        formData.append('audio', sosAudioBlob, 'sos.' + ext);
        formData.append('transcript', sosTranscript.trim());
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("sos.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(r) {
                const ct = r.headers.get('content-type') || '';
                if (ct.includes('application/json')) {
                    return r.json();
                }
                return r.text().then(function(text) {
                    throw new Error('HTTP ' + r.status + ' – server returned non-JSON. Check Laravel log. Preview: ' + text.replace(/<[^>]+>/g,'').trim().substring(0, 200));
                });
            })
            .then(function(data) {
                document.getElementById('sosSending').style.display = 'none';
                if (data.status === 'ok') {
                    document.getElementById('sosSent').style.display = 'block';
                } else {
                    alert('Failed: ' + data.message);
                    document.getElementById('sosPreview').style.display = 'block';
                }
            })
            .catch(function(err) {
                document.getElementById('sosSending').style.display = 'none';
                alert('SOS error: ' + (err && err.message ? err.message : String(err)));
                document.getElementById('sosPreview').style.display = 'block';
            });
    }

    function toggleTracking() {
        isTracking ? stopTracking() : startTracking();
    }

    function startTracking() {
        if (!navigator.geolocation) {
            document.getElementById('trackingStatus').textContent = 'Geolocation not supported by your browser.';
            return;
        }

        isTracking = true;
        const btn = document.getElementById('trackingBtn');
        btn.innerHTML = '<i class="fas fa-stop me-2"></i> Stop Tracking';
        btn.classList.add('tracking');
        document.getElementById('trackingStatus').textContent = 'Sharing your live location...';

        sendLocation();
        trackingInterval = setInterval(sendLocation, 5000);
    }

    function stopTracking() {
        isTracking = false;
        clearInterval(trackingInterval);
        const btn = document.getElementById('trackingBtn');
        btn.innerHTML = '<i class="fas fa-map-marker-alt me-2"></i> Start Tracking';
        btn.classList.remove('tracking');
        document.getElementById('trackingStatus').textContent = 'Location sharing stopped.';

        fetch('{{ route("driver.location.clear") }}', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
    }

    function sendLocation() {
        navigator.geolocation.getCurrentPosition(function (pos) {
            fetch('{{ route("driver.location.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ lat: pos.coords.latitude, lng: pos.coords.longitude })
            });
        }, function () {
            document.getElementById('trackingStatus').textContent = 'Unable to get location. Check browser permissions.';
        }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
    }
</script>
@endif

{{-- Parent: Leaflet Map JS --}}
@if(isset($userRole) && $userRole === 'P' && isset($parentDriverIds) && count($parentDriverIds) > 0)
<script>
    const driverIds      = JSON.parse('{!! json_encode($parentDriverIds) !!}');
    const schoolNames    = JSON.parse('{!! json_encode($schoolNames ?? []) !!}');
    const locationBaseUrl = '{{ url("/driver/location") }}';

    const map = L.map('map').setView([3.1390, 101.6869], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const vanIcon = L.divIcon({
        html: '<div style="background:#00b894;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.2);"><i class="fas fa-bus" style="color:#fff;font-size:16px;"></i></div>',
        className: '', iconSize: [36, 36], iconAnchor: [18, 18],
    });

    const schoolIcon = L.divIcon({
        html: '<div style="background:#0a1628;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.2);"><i class="fas fa-school" style="color:#fff;font-size:15px;"></i></div>',
        className: '', iconSize: [36, 36], iconAnchor: [18, 18],
    });

    let markers      = {};
    let schoolCoords = []; // [{name, lat, lng}]
    let arrivalShown = false;

    // Haversine distance in metres
    function distanceM(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2)*Math.sin(dLat/2) +
                  Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*
                  Math.sin(dLon/2)*Math.sin(dLon/2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    function dismissArrival() {
        document.getElementById('arrivalAlert').style.display = 'none';
        arrivalShown = false;
    }

    function showArrivalBanner(schoolName) {
        if (arrivalShown) return;
        arrivalShown = true;
        const box = document.getElementById('arrivalAlert');
        document.getElementById('arrivalAlertText').textContent = 'Van has arrived at ' + schoolName + '!';
        box.style.display = 'flex';
        // Auto-dismiss after 30 seconds
        setTimeout(function() {
            box.style.display = 'none';
            arrivalShown = false;
        }, 30000);
    }

    function checkArrival(vanLat, vanLng) {
        schoolCoords.forEach(function(school) {
            const dist = distanceM(vanLat, vanLng, school.lat, school.lng);
            if (dist <= 150) {
                showArrivalBanner(school.name);
            }
        });
    }

    // Geocode school names using Nominatim (free, no key needed)
    function geocodeSchools() {
        schoolNames.forEach(function(name) {
            if (!name) return;
            fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(name))
                .then(r => r.json())
                .then(function(results) {
                    if (results && results.length > 0) {
                        const lat = parseFloat(results[0].lat);
                        const lng = parseFloat(results[0].lon);
                        schoolCoords.push({ name: name, lat: lat, lng: lng });
                        L.marker([lat, lng], { icon: schoolIcon })
                            .addTo(map)
                            .bindPopup('<b>' + name + '</b><br>School destination');
                    }
                })
                .catch(function() {});
        });
    }

    function fetchLocations() {
        driverIds.forEach(function (driverId) {
            fetch(`${locationBaseUrl}/${driverId}`)
                .then(r => r.json())
                .then(function(data) {
                    if (data.status === 'ok') {
                        const latlng = [data.lat, data.lng];
                        if (markers[driverId]) {
                            markers[driverId].setLatLng(latlng);
                        } else {
                            markers[driverId] = L.marker(latlng, { icon: vanIcon })
                                .addTo(map)
                                .bindPopup('Your driver\'s van');
                            map.setView(latlng, 14);
                        }
                        document.getElementById('mapStatus').textContent = 'Last updated: ' + new Date(data.timestamp).toLocaleTimeString();
                        checkArrival(data.lat, data.lng);
                    } else {
                        if (markers[driverId]) {
                            map.removeLayer(markers[driverId]);
                            delete markers[driverId];
                        }
                        document.getElementById('mapStatus').textContent = 'Driver is not sharing location.';
                    }
                });
        });
    }

    geocodeSchools();
    fetchLocations();
    setInterval(fetchLocations, 5000);
</script>
@endif

{{-- Driver location info modal --}}
@if(isset($driver) && $driver && (empty($driver->city) || empty($driver->district)))
<div class="modal fade" id="locationModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; overflow:hidden; border:1.5px solid rgba(0,184,148,0.25);">
            <div class="modal-header" style="background:#e6f9f5; border-bottom:1.5px solid rgba(0,184,148,0.25);">
                <h5 class="modal-title" id="locationModalLabel" style="font-family:'Syne',sans-serif; font-weight:700; color:#007a63;">
                    <i class="fas fa-map-marker-alt me-2"></i> Complete Your Profile
                </h5>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-4" style="font-size:0.95rem;">Please fill in your city and district so parents can find your schedule locations.</p>
                <form action="{{ route('driver.update-location-info') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-weight:600; color:#0a1628;">City</label>
                        <input type="text" name="city" class="form-control" placeholder="e.g. Johor Bahru" required
                               style="border:1.5px solid rgba(0,184,148,0.35); border-radius:8px; padding:10px 14px;">
                    </div>
                    <div class="mb-4">
                        <label class="form-label" style="font-weight:600; color:#0a1628;">District</label>
                        <input type="text" name="district" class="form-control" placeholder="e.g. Skudai" required
                               style="border:1.5px solid rgba(0,184,148,0.35); border-radius:8px; padding:10px 14px;">
                    </div>
                    <button type="submit" class="btn w-100" style="background:linear-gradient(135deg,#00b894,#007a63); color:#fff; font-weight:700; border-radius:8px; padding:10px;">
                        Save & Continue
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = new bootstrap.Modal(document.getElementById('locationModal'));
        modal.show();
    });
</script>
@endif

@endsection

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
    :root {
      --emerald:    #00b894;
      --emerald-dk: #007a63;
      --emerald-lt: #e6f9f5;
      --navy:       #0a1628;
      --slate:      #4a5568;
      --white:      #ffffff;
      --bg:         #f5f7fa;
      --border:     rgba(0,184,148,0.25);
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow-x: hidden;
      position: relative;
    }

    /* --- Background Animations (Same as Register) --- */
    .road-strip {
      position: fixed; bottom: 60px; left: 0; right: 0;
      height: 52px; z-index: 1; pointer-events: none;
    }
    .road-surface {
      position: absolute; inset: 0;
      background: rgba(0,184,148,0.06);
      border-top: 2px solid rgba(0,184,148,0.22);
      border-bottom: 2px solid rgba(0,184,148,0.22);
    }
    .road-dash {
      position: absolute; top: 50%; left: 0;
      width: 200%; height: 2px;
      background: repeating-linear-gradient(90deg, rgba(0,184,148,0.45) 0px, rgba(0,184,148,0.45) 40px, transparent 40px, transparent 70px);
      animation: dashMove 1.1s linear infinite;
    }
    @keyframes dashMove {
      from { transform: translateY(-50%) translateX(0); }
      to   { transform: translateY(-50%) translateX(-70px); }
    }
    .glow-tr {
      position: fixed; width: 500px; height: 500px; top: -120px; right: -120px;
      background: radial-gradient(circle, rgba(0,184,148,0.1) 0%, transparent 70%);
      z-index: 0;
    }
    .glow-bl {
      position: fixed; width: 350px; height: 350px; bottom: -100px; left: -80px;
      background: radial-gradient(circle, rgba(0,184,148,0.07) 0%, transparent 70%);
      z-index: 0;
    }

    /* --- Card Style (Same as Register) --- */
    .register-wrap {
      position: relative; z-index: 10;
      width: 100%; max-width: 800px;
      padding: 1.5rem;
      animation: fadeUp 0.65s ease both;
    }
    .register-card {
      background: var(--white);
      border: 1.5px solid var(--border);
      border-radius: 20px;
      padding: 3rem 2.5rem;
      box-shadow: 0 8px 32px rgba(0,184,148,0.08);
      transition: transform 0.3s ease;
    }
    .register-card:hover { transform: translateY(-5px); }

    .brand-top { display: flex; align-items: center; gap: 0.65rem; margin-bottom: 2rem; }
    .brand-top img { height: 34px; }
    .brand-top .name {
      font-family: 'Syne', sans-serif;
      font-size: 1.3rem; font-weight: 800;
      color: var(--navy); letter-spacing: 0.04em;
    }
    .brand-top .name span { color: var(--emerald); }

    h2 { font-family: 'Syne', sans-serif; font-weight: 800; color: var(--navy); }
    h2 span { color: var(--emerald); }
    
    .sub { font-size: 1rem; color: var(--slate); font-style: italic; }
    .status-text { font-size: 1.1rem; color: var(--navy); font-weight: 500; }

    .divider {
      display: flex; align-items: center; gap: 0.75rem;
      margin: 2rem 0;
    }
    .divider::before, .divider::after {
      content: ''; flex: 1; height: 1px; background: #e8ecf0;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(28px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .btn-track {
        background: linear-gradient(135deg, #00b894, #007a63);
        color: #fff; border: none; font-weight: 700;
        padding: 10px 24px; border-radius: 10px;
        font-family: 'Syne', sans-serif;
        box-shadow: 0 4px 15px rgba(0,184,148,0.25);
        transition: all 0.3s;
    }
    .btn-track:hover { opacity: 0.88; color: #fff; transform: translateY(-1px); }
    .btn-track.tracking {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        box-shadow: 0 4px 15px rgba(231,76,60,0.25);
    }
    /* SOS Floating Action Button */
    .btn-sos-fab {
        position: fixed;
        bottom: 28px;
        right: 28px;
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: #fff;
        border: 3px solid rgba(255,255,255,0.3);
        box-shadow: 0 6px 24px rgba(231,76,60,0.55);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 9999;
        animation: sosPulse 2s ease-in-out infinite;
        transition: transform 0.2s, box-shadow 0.2s;
        text-decoration: none;
        outline: none;
    }
    .btn-sos-fab:hover { transform: scale(1.12); box-shadow: 0 10px 32px rgba(231,76,60,0.7); }
    .btn-sos-fab i { font-size: 1.55rem; line-height: 1; }
    .sos-fab-label { font-size: 0.58rem; font-weight: 800; letter-spacing: 0.08em; margin-top: 2px; text-transform: uppercase; }
    @keyframes sosPulse {
        0%, 100% { box-shadow: 0 6px 24px rgba(231,76,60,0.55); }
        50%       { box-shadow: 0 6px 36px rgba(231,76,60,0.85), 0 0 0 12px rgba(231,76,60,0.12); }
    }

    /* SOS Fixed Panel */
    .sos-panel-fixed {
        position: fixed;
        bottom: 118px;
        right: 20px;
        width: 320px;
        max-width: calc(100vw - 40px);
        background: #fff3f3;
        border: 1.5px solid #e17055;
        border-radius: 16px;
        padding: 1.25rem;
        z-index: 9998;
        box-shadow: 0 8px 32px rgba(225,112,85,0.28);
        text-align: left;
        animation: panelSlideUp 0.25s ease both;
    }
    @keyframes panelSlideUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .btn-record-sos {
        background: linear-gradient(135deg, #e17055, #c0392b);
        color: #fff; border: none; font-weight: 600;
        padding: 8px 20px; border-radius: 8px; cursor: pointer;
        transition: opacity 0.2s;
    }
    .btn-record-sos:hover { opacity: 0.88; }
    .btn-stop-sos {
        background: #636e72; color: #fff; border: none;
        font-weight: 600; padding: 8px 20px; border-radius: 8px; cursor: pointer;
    }
    .btn-send-sos {
        background: linear-gradient(135deg, #e17055, #c0392b);
        color: #fff; border: none; font-weight: 600;
        padding: 8px 20px; border-radius: 8px; cursor: pointer;
    }
    .btn-rerecord-sos {
        background: #b2bec3; color: #2d3436; border: none;
        font-weight: 600; padding: 8px 20px; border-radius: 8px; cursor: pointer;
    }
    .sos-blink { animation: sosBlink 1s step-start infinite; color: #e17055; }
    @keyframes sosBlink { 50% { opacity: 0; } }

    /* --- Mobile Responsive --- */
    @media (max-width: 576px) {
      .register-card { padding: 2.5rem 1.5rem; }
      h2 { font-size: 1.6rem; }
      .brand-top img { height: 28px; }
    }
</style>

@if(isset($userRole) && $userRole === 'P' && isset($parentDriverIds) && count($parentDriverIds) > 0)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endif
@endsection