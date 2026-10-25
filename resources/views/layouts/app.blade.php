<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BAUST Complaint Management')</title>
    <style>
        :root{--blue:#315d9b;--blue-dark:#234777;--blue-soft:#eaf2ff;--bg:#f5f7fb;--text:#253247;--muted:#69778b;--line:#e2e8f0;--white:#fff;--green:#e7f7ee;--orange:#fff3dc;--red:#fdecec}
        *{box-sizing:border-box}body{margin:0;background:var(--bg);font-family:Arial,Helvetica,sans-serif;color:var(--text);line-height:1.55}
        a{color:var(--blue);text-decoration:none}a:hover{text-decoration:underline}.topbar{background:#fff;border-bottom:1px solid var(--line);position:sticky;top:0;z-index:5}
        .nav{max-width:1160px;margin:auto;padding:14px 22px;display:flex;align-items:center;justify-content:space-between;gap:18px}
        .brand{font-size:17px;font-weight:800;color:var(--blue-dark);display:flex;align-items:center;gap:10px}.brand-mark{background:var(--blue);color:#fff;border-radius:10px;padding:7px 10px;font-weight:bold}
        .navlinks{display:flex;align-items:center;gap:18px;flex-wrap:wrap}.navlinks a{color:#44536a;font-size:14px}.navform{display:inline}.navform button{border:0;background:none;color:#44536a;cursor:pointer;font-size:14px}
        .container{max-width:1160px;margin:30px auto;padding:0 22px;min-height:70vh}.hero{background:linear-gradient(120deg,#eaf2ff,#f9fbff);border:1px solid #dce8fb;border-radius:18px;padding:42px;margin-bottom:24px}
        h1,h2,h3{line-height:1.25;margin-top:0}h1{font-size:30px}h2{font-size:22px}h3{font-size:17px}.muted,.small{color:var(--muted)}.small{font-size:13px}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;border:1px solid var(--blue);background:var(--blue);color:#fff!important;border-radius:9px;padding:10px 15px;font-weight:700;font-size:14px;cursor:pointer;text-decoration:none}
        .btn:hover{background:var(--blue-dark);text-decoration:none}.btn.secondary{background:#fff;color:var(--blue)!important;border-color:#cbd8ec}.btn.danger{background:#fff;color:#a52d35!important;border-color:#efc4c7}.btn.smallbtn{padding:7px 10px;font-size:12px}
        .card{background:#fff;border:1px solid var(--line);border-radius:14px;padding:22px;box-shadow:0 4px 16px rgba(35,60,100,.035);margin-bottom:18px}
        .grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.grid.two{grid-template-columns:repeat(2,minmax(0,1fr))}.stat{background:#fff;border:1px solid var(--line);border-radius:14px;padding:19px}.stat strong{font-size:27px;display:block;color:var(--blue-dark)}.stat span{font-size:13px;color:var(--muted)}
        .form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.field{margin-bottom:15px}.field label{display:block;font-weight:700;font-size:13px;margin-bottom:6px}.field input,.field select,.field textarea,.filter input,.filter select{width:100%;border:1px solid #d5deea;background:#fff;border-radius:8px;padding:11px 12px;font:inherit;color:var(--text)}.field textarea{min-height:130px;resize:vertical}.field input:focus,.field select:focus,.field textarea:focus{outline:2px solid #c5d9ff;border-color:var(--blue)}
        .error{color:#b4232c;font-size:13px;margin-top:4px}.alert{padding:12px 15px;border-radius:9px;margin-bottom:18px}.alert.success{background:#eaf8ef;color:#17683b;border:1px solid #c7ecd3}.alert.errorbox{background:#fff0f0;color:#9e2930;border:1px solid #f4cece}
        .table-wrap{overflow-x:auto}table{border-collapse:collapse;width:100%;font-size:13px}th,td{text-align:left;padding:13px 12px;border-bottom:1px solid var(--line);vertical-align:top}th{background:#f8faff;color:#627086;font-size:11px;text-transform:uppercase;letter-spacing:.04em}tr:last-child td{border-bottom:0}
        .badge{display:inline-block;border-radius:20px;padding:4px 9px;font-size:11px;font-weight:800;white-space:nowrap}.badge.pending{background:var(--orange);color:#94600b}.badge.progress{background:var(--blue-soft);color:#2456a0}.badge.resolved{background:var(--green);color:#1f7746}
        .section-head{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:18px}.filter{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:17px}.filter input,.filter select{width:auto;min-width:160px;padding:9px}
        .timeline{border-left:2px solid #dbe6f7;margin-left:7px;padding-left:20px}.event{position:relative;padding-bottom:18px}.event:before{content:"";position:absolute;width:10px;height:10px;border-radius:50%;background:var(--blue);left:-26px;top:6px}.footer{text-align:center;color:#8490a1;padding:25px;border-top:1px solid var(--line);font-size:12px;background:#fff;margin-top:35px}
        .empty{padding:30px;text-align:center;color:var(--muted)}.inline{display:inline-flex;gap:8px;align-items:center}.pagination{margin-top:18px}.pagination nav{display:flex;justify-content:space-between}
        @media(max-width:800px){.grid{grid-template-columns:repeat(2,minmax(0,1fr))}.nav{align-items:flex-start;flex-direction:column}.hero{padding:28px}.form-grid,.grid.two{grid-template-columns:1fr}}
        @media(max-width:480px){.grid{grid-template-columns:1fr}.container{padding:0 14px;margin-top:20px}.hero h1{font-size:25px}}
    </style>
</head>
<body>
<header class="topbar">
    <nav class="nav">
        <a class="brand" href="{{ route('home') }}"><span class="brand-mark">B</span><span>BAUST <span class="muted" style="font-weight:400">| Complaints</span></span></a>
        <div class="navlinks">
            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
                @if(auth()->user()->role === 'student')
                    <a href="{{ route('complaints.index') }}">My Complaints</a>
                    <a href="{{ route('complaints.create') }}">Submit Complaint</a>
                @else
                    <a href="{{ route('admin.complaints.index') }}">Assigned Complaints</a>
                @endif
                @if(auth()->user()->role === 'super_admin')
                    <a href="{{ route('superadmin.categories') }}">Categories</a>
                    <a href="{{ route('superadmin.administrators') }}">Administrators</a>
                @endif
                <span class="small">{{ auth()->user()->name }}</span>
                <form class="navform" method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Log out</button></form>
            @else
                <a href="{{ route('login') }}">Log in</a><a class="btn smallbtn" href="{{ route('register') }}">Student Register</a>
            @endauth
        </div>
    </nav>
</header>
<main class="container">
    @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert errorbox"><strong>Please correct the following:</strong><ul style="margin-bottom:0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @yield('content')
</main>
<footer class="footer">BAUST Complaint Management System · Bangladesh Army University of Science and Technology · © {{ date('Y') }}</footer>
</body>
</html>
