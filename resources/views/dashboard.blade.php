<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neon Dashboard | Pro</title>
    <style>
        body {
            background-color: #0d0d0d;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            display: flex;
            overflow-x: hidden;
        }

        /* Sidebar Style */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #1a1a1a;
            border-right: 2px solid #00f2fe;
            padding: 20px;
            box-shadow: 5px 0 15px rgba(0, 242, 254, 0.2);
            position: fixed;
        }

        .sidebar h2 {
            color: #00f2fe;
            text-shadow: 0 0 10px #00f2fe;
            text-align: center;
            margin-bottom: 50px;
        }

        .sidebar-item {
            margin-top: 20px;
            color: #ccc;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 8px;
        }

        .sidebar-item:hover { background: rgba(0, 242, 254, 0.1); color: #00f2fe; }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 40px;
            margin-left: 280px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        /* Stats Cards */
        .card {
            background: #1a1a1a;
            padding: 25px;
            border-radius: 15px;
            border: 1px solid #4facfe;
            flex: 1;
            box-shadow: 0 0 15px rgba(79, 172, 254, 0.2);
            text-align: center;
            transition: 0.3s;
        }

        .card h3 { color: #4facfe; margin: 0; font-size: 16px; text-transform: uppercase; }
        .card p { font-size: 32px; font-weight: bold; margin: 15px 0 0; }

        /* Settings Style */
        .settings-input {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            background: #222;
            border: 1px solid #444;
            color: #fff;
            border-radius: 8px;
            outline: none;
            box-sizing: border-box;
        }
        .settings-input:focus { border-color: #00f2fe; box-shadow: 0 0 5px #00f2fe; }

        .profile-upload-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            gap: 10px;
        }

        .profile-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid #00f2fe;
            object-fit: cover;
            background: #333;
            box-shadow: 0 0 10px rgba(0, 242, 254, 0.3);
        }

        .logout-btn {
            background: transparent;
            color: #ff0055;
            border: 2px solid #ff0055;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 0 10px #ff0055;
            transition: 0.3s;
        }
        .logout-btn:hover { background: #ff0055; color: #fff; }

        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    </style>
</head>
<body>

    @if(session('tracking_started'))
    <div id="time-popup" style="position: fixed; top: 20px; right: 20px; background: #1a1a1a; color: #00f2fe; padding: 15px 25px; border-radius: 10px; font-weight: bold; z-index: 9999; box-shadow: 0 0 20px rgba(0,242,254,0.4); border-left: 5px solid #00f2fe; animation: slideIn 0.5s ease-out;">
        ⏱️ {{ session('tracking_started') }}
    </div>
    <script>setTimeout(() => { document.getElementById('time-popup').style.removeProperty('animation'); document.getElementById('time-popup').style.opacity = '0'; setTimeout(()=>document.getElementById('time-popup').remove(), 500); }, 4000);</script>
    @endif

    <div class="sidebar">
        <h2>STUDENT PRO</h2>
        <p style="color:#666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Main Menu</p>
        
        <div id="dash-btn" class="sidebar-item" style="color:#00f2fe;">🏠 Dashboard</div>
        <div id="time-btn" class="sidebar-item">⏱️ Time Tracker</div>
        <div id="settings-btn" class="sidebar-item">⚙️ Settings</div>
    </div>

    <div class="main-content">
        
        <div class="header">
            <div class="welcome-text">Welcome, <span style="color:#00f2fe;">{{ Auth::user()->name }}</span>!</div>
            
            <div style="display: flex; align-items: center; gap: 15px;">
                <form action="{{ route('status.toggle') }}" method="POST">
                    @csrf
                    <button type="submit" style="background: transparent; color: {{ Auth::user()->status == 'Online' ? '#00ff88' : '#ff0055' }}; border: 2px solid {{ Auth::user()->status == 'Online' ? '#00ff88' : '#ff0055' }}; padding: 8px 15px; border-radius: 20px; cursor: pointer; font-weight: bold; box-shadow: 0 0 10px {{ Auth::user()->status == 'Online' ? '#00ff88' : '#ff0055' }};">
                        Server: {{ Auth::user()->status ?? 'Online' }}
                    </button>
                </form>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>

        <div id="dashboard-view">
            <div style="display: flex; gap: 20px;">
                <div class="card">
                    <h3>User ID</h3>
                    <p>#{{ Auth::user()->id }}</p> 
                </div>
                <div class="card">
                    <h3>Total Users</h3> 
                    <p>{{ \App\Models\User::count() }}</p> 
                </div>
                <div class="card" style="border-color: {{ Auth::user()->status == 'Online' ? '#00ff88' : '#ff0055' }};">
                    <h3 style="color: {{ Auth::user()->status == 'Online' ? '#00ff88' : '#ff0055' }};">Server Status</h3>
                    <p style="color: {{ Auth::user()->status == 'Online' ? '#00ff88' : '#ff0055' }};">
                        {{ Auth::user()->status ?? 'Online' }}
                    </p>
                </div>
            </div>
        </div>

        <div id="time-view" style="display: none;">
            <div id="tracker-container" style="background:#1a1a1a; padding:50px; border-radius:15px; border: 1px solid #ff0055; text-align: center; transition: 0.3s;">
                <h2 id="tracker-title" style="color: #ff0055; margin-bottom: 30px; transition: 0.3s;">⏱️ DAILY TIME LOG</h2>
                
                @php
                    $log = \App\Models\TimeLog::where('user_id', Auth::id())->where('log_date', now()->toDateString())->first();
                    $sec = $log ? (int)$log->total_seconds : 0;
                    $startTimeText = ($log && $log->login_at) ? \Carbon\Carbon::parse($log->login_at)->format('h:i A') : '--:--';
                    if($log && $log->login_at && !$log->logout_at) { 
                        $sec += (int)now()->diffInSeconds($log->login_at); 
                    }
                @endphp

                <div id="live-timer" style="font-size: 80px; font-family: monospace; color: #fff; transition: 0.3s;">
                    00:00:00
                </div>

                <div style="margin-top: 20px; display: flex; justify-content: center; gap: 40px; border-top: 1px solid #333; padding-top: 20px;">
                    <div>
                        <p style="color: #666; margin: 0; font-size: 12px; text-transform: uppercase;">Started At</p>
                        <p id="session-start" style="color: #00f2fe; font-weight: bold; font-size: 18px; margin: 5px 0 0;">{{ $startTimeText }}</p>
                    </div>
                    <div>
                        <p style="color: #666; margin: 0; font-size: 12px; text-transform: uppercase;">Last Updated</p>
                        <p id="session-end" style="color: #00f2fe; font-weight: bold; font-size: 18px; margin: 5px 0 0;">--:--</p>
                    </div>
                </div>

                <div id="pause-notice" style="display: none; margin-top: 20px; padding: 10px; background: rgba(255,0,85,0.1); border-radius: 8px;">
                    <p style="color: #ff0055; font-weight: bold; margin: 0;">Timer Paused. Recorded for today: <span id="recorded-time">00:00:00</span></p>
                </div>

                <p style="color: #666; margin-top: 30px;">Tracking your activity for today: <b>{{ now()->format('d M, Y') }}</b></p>
            </div>
        </div>

        <div id="settings-view" style="display: none;">
            <div style="background:#1a1a1a; padding:30px; border-radius:15px; border: 1px solid #00f2fe; max-width: 500px;">
                <h3 style="color: #00f2fe; margin-bottom: 25px;">⚙️ Account Settings</h3>
                
                <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="profile-upload-section">
                        <img id="img-preview" src="{{ Auth::user()->profile_photo_url ?? 'https://via.placeholder.com/100' }}" class="profile-preview" alt="Profile">
                        <label style="color:#888; font-size: 13px; cursor: pointer;">
                            <span style="color:#00f2fe;">+ Change Photo</span>
                            <input type="file" name="profile_photo" style="display:none;" onchange="previewImage(this)">
                        </label>
                    </div>

                    <label style="color:#888;">Full Name</label>
                    <input type="text" name="name" class="settings-input" value="{{ Auth::user()->name }}">
                    
                    <label style="color:#888;">Email Address</label>
                    <input type="email" name="email" class="settings-input" value="{{ Auth::user()->email }}">
                    
                    <label style="color:#888;">New Password</label>
                    <input type="password" name="password" class="settings-input" placeholder="Leave blank to keep current">
                    
                    <button type="submit" style="background:#00f2fe; color:#000; border:none; padding:15px; border-radius:5px; cursor:pointer; font-weight:bold; width:100%;">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

   <script>
    const btns = { 
        dash: document.getElementById('dash-btn'), 
        time: document.getElementById('time-btn'), 
        set: document.getElementById('settings-btn') 
    };
    const views = { 
        dash: document.getElementById('dashboard-view'), 
        time: document.getElementById('time-view'), 
        set: document.getElementById('settings-view') 
    };

    function switchView(viewName) {
        Object.values(views).forEach(v => v.style.display = 'none');
        Object.values(btns).forEach(b => b.style.color = '#ccc');
        views[viewName].style.display = 'block';
        btns[viewName].style.color = '#00f2fe';
    }

    btns.dash.onclick = () => switchView('dash');
    btns.time.onclick = () => switchView('time');
    btns.set.onclick = () => switchView('set');

    // Image Preview Function
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('img-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // --- TIMER LOGIC ---
    let totalSeconds = parseInt("{{ $sec }}"); 
    const timerDisplay = document.getElementById('live-timer');
    const trackerContainer = document.getElementById('tracker-container');
    const trackerTitle = document.getElementById('tracker-title');
    const endDisplay = document.getElementById('session-end');
    const pauseNotice = document.getElementById('pause-notice');
    const recordedDisplay = document.getElementById('recorded-time');
    
    let isServerOnline = "{{ Auth::user()->status }}" === "Online";

    function formatTime(secs) {
        secs = Math.floor(secs);
        let h = Math.floor(secs / 3600);
        let m = Math.floor((secs % 3600) / 60);
        let s = secs % 60;
        return (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s);
    }

    function applyStatusStyles() {
        if (isServerOnline) {
            timerDisplay.style.textShadow = "0 0 20px #00ff88";
            trackerTitle.style.color = "#00ff88";
            trackerContainer.style.borderColor = "#00ff88";
            trackerContainer.style.boxShadow = "0 0 30px rgba(0, 255, 136, 0.1)";
        } else {
            timerDisplay.style.textShadow = "0 0 20px #ff0055";
            trackerTitle.style.color = "#ff0055";
            trackerContainer.style.borderColor = "#ff0055";
            trackerContainer.style.boxShadow = "0 0 30px rgba(255, 0, 85, 0.1)";
        }
    }

    function updateTimer() {
        if (isServerOnline) {
            totalSeconds++; 
            if(timerDisplay) timerDisplay.innerText = formatTime(totalSeconds);
            let now = new Date();
            if(endDisplay) endDisplay.innerText = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            if(pauseNotice) pauseNotice.style.display = 'none';
        } else {
            if(pauseNotice) {
                pauseNotice.style.display = 'block';
                recordedDisplay.innerText = formatTime(totalSeconds);
            }
        }
    }

    applyStatusStyles();
    setInterval(updateTimer, 1000);
    if(timerDisplay) timerDisplay.innerText = formatTime(totalSeconds);
</script>

</body>
</html>