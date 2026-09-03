@extends('layouts.index')

@section('css')
    <style>
        :root {
            --primary: #1E6F78;
            --secondary: #287F88;
            --dark: #145962;
            --accent: #2C8F99;
            --success: #22C55E;
            --warning: #F59E0B;
            --danger: #EF4444;
            --bg: #F8FAFC;
            --card: #FFFFFF;
            --ink: #0F172A;
            --muted: #64748B;
            --border: #E2E8F0;
            --grad: linear-gradient(135deg, #1E6F78 0%, #2C8F99 100%);
            --grad-soft: linear-gradient(135deg, rgba(30, 111, 120, .08), rgba(44, 143, 153, .04));
            --shadow-sm: 0 1px 2px rgba(15, 23, 42, .06), 0 1px 3px rgba(15, 23, 42, .05);
            --shadow: 0 10px 30px -12px rgba(15, 23, 42, .15), 0 4px 12px -4px rgba(15, 23, 42, .08);
            --shadow-lg: 0 25px 60px -20px rgba(20, 89, 98, .35);
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            background: var(--bg);
            color: var(--ink);
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 14px
        }

        body {
            overflow-x: hidden;
            position: relative;
            min-height: 100vh
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        .font-display {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            letter-spacing: -.01em
        }

        /* Backdrop */
        .bg-scene {
            position: fixed;
            inset: 0;
            z-index: -1;
            overflow: hidden;
            pointer-events: none
        }

        .bg-scene::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(1200px 600px at 10% -10%, rgba(30, 111, 120, .14), transparent 60%),
                radial-gradient(900px 500px at 100% 0%, rgba(44, 143, 153, .12), transparent 60%),
                radial-gradient(700px 700px at 50% 120%, rgba(20, 89, 98, .10), transparent 60%),
                linear-gradient(180deg, #F8FAFC 0%, #EEF4F5 100%)
        }

        .bg-scene::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(30, 111, 120, .05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30, 111, 120, .05) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: radial-gradient(ellipse at center, black 40%, transparent 80%)
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .5;
            animation: float 18s ease-in-out infinite
        }

        .blob.b1 {
            width: 520px;
            height: 520px;
            background: #2C8F99;
            top: -160px;
            left: -140px
        }

        .blob.b2 {
            width: 420px;
            height: 420px;
            background: #1E6F78;
            bottom: -140px;
            right: -120px;
            animation-delay: -6s
        }

        .blob.b3 {
            width: 360px;
            height: 360px;
            background: #145962;
            top: 40%;
            left: 55%;
            opacity: .25;
            animation-delay: -12s
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0)
            }

            50% {
                transform: translate(30px, -40px)
            }
        }

        .particle {
            position: absolute;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: rgba(30, 111, 120, .35);
            animation: rise linear infinite
        }

        @keyframes rise {
            0% {
                transform: translateY(20px);
                opacity: 0
            }

            10% {
                opacity: .6
            }

            100% {
                transform: translateY(-100vh);
                opacity: 0
            }
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 250px;
            background: rgba(255, 255, 255, .85);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--border);
            padding: 22px 16px;
            z-index: 100;
            display: flex;
            flex-direction: column
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 8px;
            margin-bottom: 22px
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: var(--grad);
            display: grid;
            place-items: center;
            color: #fff;
            box-shadow: var(--shadow)
        }

        .brand-name {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: .02em
        }

        .brand-sub {
            font-size: 10px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .14em
        }

        .nav-section {
            font-size: 10px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .14em;
            padding: 12px 10px 6px
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 11px;
            border-radius: 10px;
            color: #334155;
            text-decoration: none;
            font-weight: 500;
            font-size: 13.5px;
            margin-bottom: 2px;
            transition: .2s
        }

        .nav-item i {
            width: 18px;
            text-align: center;
            color: var(--primary);
            opacity: .8
        }

        .nav-item:hover {
            background: var(--grad-soft);
            color: var(--primary)
        }

        .nav-item.active {
            background: var(--grad);
            color: #fff;
            box-shadow: 0 8px 20px -8px rgba(30, 111, 120, .5)
        }

        .nav-item.active i {
            color: #fff;
            opacity: 1
        }

        .side-foot {
            margin-top: auto;
            padding: 12px;
            border-radius: 14px;
            background: var(--grad);
            color: #fff;
            position: relative;
            overflow: hidden
        }

        .side-foot::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 100% 0%, rgba(255, 255, 255, .25), transparent 60%)
        }

        /* Main */
        .main {
            margin-left: 250px;
            padding: 22px 26px 40px
        }

        .topbar {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px
        }

        .search {
            flex: 1;
            position: relative;
            max-width: 520px
        }

        .search input {
            width: 100%;
            height: 42px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, .7);
            backdrop-filter: blur(10px);
            padding: 0 14px 0 40px;
            font-size: 13.5px;
            transition: .2s
        }

        .search input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(30, 111, 120, .12);
            background: #fff
        }

        .search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted)
        }

        .icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .75);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            display: grid;
            place-items: center;
            color: #334155;
            position: relative;
            cursor: pointer;
            transition: .2s
        }

        .icon-btn:hover {
            color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-1px)
        }

        .icon-btn .dot {
            position: absolute;
            top: 9px;
            right: 10px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--danger);
            border: 2px solid #fff
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--grad);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 600;
            box-shadow: var(--shadow)
        }

        /* Header */
        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 22px
        }

        .page-head h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            background: linear-gradient(90deg, #0F172A, #1E6F78);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent
        }

        .page-head p {
            color: var(--muted);
            margin: 6px 0 0;
            max-width: 640px;
            font-size: 14px
        }

        .breadcrumb-lite {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 6px
        }

        .breadcrumb-lite b {
            color: var(--primary)
        }

        .head-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap
        }

        .btn-brand {
            background: var(--grad);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 11px 18px;
            font-weight: 600;
            font-size: 13.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 12px 28px -12px rgba(30, 111, 120, .55);
            transition: .2s;
            position: relative;
            overflow: hidden
        }

        .btn-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 36px -14px rgba(30, 111, 120, .7);
            color: #fff
        }

        .btn-brand::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 30%, rgba(255, 255, 255, .35) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: .6s
        }

        .btn-brand:hover::after {
            transform: translateX(100%)
        }

        .btn-ghost {
            background: rgba(255, 255, 255, .8);
            color: #334155;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 11px 16px;
            font-weight: 600;
            font-size: 13.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: .2s
        }

        .btn-ghost:hover {
            color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-1px);
            background: #fff
        }

        /* KPI */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 22px
        }

        .kpi {
            position: relative;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 18px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: .3s;
            opacity: 0;
            transform: translateY(14px);
            animation: fadeUp .6s forwards
        }

        .kpi:nth-child(1) {
            animation-delay: .05s
        }

        .kpi:nth-child(2) {
            animation-delay: .15s
        }

        .kpi:nth-child(3) {
            animation-delay: .25s
        }

        .kpi:nth-child(4) {
            animation-delay: .35s
        }

        .kpi::before {
            content: "";
            position: absolute;
            inset: 0;
            background: var(--grad-soft);
            opacity: 0;
            transition: .3s
        }

        .kpi:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: transparent
        }

        .kpi:hover::before {
            opacity: 1
        }

        .kpi-inner {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px
        }

        .kpi-ico {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--grad);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 17px;
            box-shadow: 0 10px 22px -10px rgba(30, 111, 120, .6)
        }

        .kpi-ico.ok {
            background: linear-gradient(135deg, #22C55E, #16A34A);
            box-shadow: 0 10px 22px -10px rgba(34, 197, 94, .6)
        }

        .kpi-ico.warn {
            background: linear-gradient(135deg, #F59E0B, #D97706);
            box-shadow: 0 10px 22px -10px rgba(245, 158, 11, .6)
        }

        .kpi-ico.dark {
            background: linear-gradient(135deg, #145962, #1E6F78)
        }

        .kpi-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .08em
        }

        .kpi-value {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 28px;
            font-weight: 700;
            margin-top: 6px;
            line-height: 1
        }

        .kpi-delta {
            margin-top: 8px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: var(--success)
        }

        .kpi-delta.down {
            color: var(--danger)
        }

        .kpi-spark {
            margin-top: 10px;
            height: 26px;
            position: relative;
            z-index: 1
        }

        /* Filter bar */
        .filters {
            background: rgba(255, 255, 255, .72);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, .6);
            border-radius: 18px;
            padding: 14px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(6, 1fr) auto;
            gap: 10px;
            align-items: center
        }

        .filter {
            position: relative
        }

        .filter select,
        .filter input {
            width: 100%;
            height: 40px;
            border-radius: 11px;
            border: 1px solid var(--border);
            background: #fff;
            padding: 0 12px;
            font-size: 13px;
            color: #0F172A;
            transition: .2s;
            appearance: none;
            -webkit-appearance: none
        }

        .filter select {
            padding-right: 32px;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
            background-repeat: no-repeat;
            background-position: right 12px center
        }

        .filter select:focus,
        .filter input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(30, 111, 120, .12)
        }

        .filter label {
            position: absolute;
            top: -8px;
            left: 10px;
            background: #fff;
            padding: 0 6px;
            font-size: 10px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .08em;
            border-radius: 4px
        }

        .btn-clear {
            height: 40px;
            padding: 0 14px;
            border-radius: 11px;
            background: var(--grad);
            color: #fff;
            border: none;
            font-weight: 600;
            font-size: 12.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: .2s;
            white-space: nowrap
        }

        .btn-clear:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px -10px rgba(30, 111, 120, .6)
        }

        /* Layout */
        .work {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px
        }

        .cal-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden
        }

        .cal-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            gap: 12px;
            flex-wrap: wrap;
            background: linear-gradient(180deg, rgba(30, 111, 120, .03), transparent)
        }

        .cal-title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .cal-nav {
            display: flex;
            gap: 6px
        }

        .cal-nav button {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            color: #334155;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: .2s
        }

        .cal-nav button:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--grad-soft)
        }

        .cal-nav .today {
            width: auto;
            padding: 0 14px;
            font-weight: 600;
            font-size: 12.5px
        }

        .cal-views {
            display: flex;
            gap: 4px;
            padding: 4px;
            background: #F1F5F9;
            border-radius: 12px
        }

        .cal-views button {
            padding: 8px 14px;
            border: none;
            background: transparent;
            border-radius: 9px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--muted);
            cursor: pointer;
            transition: .2s
        }

        .cal-views button:hover {
            color: var(--primary)
        }

        .cal-views button.active {
            background: var(--grad);
            color: #fff;
            box-shadow: 0 6px 14px -6px rgba(30, 111, 120, .5)
        }

        #calendar {
            padding: 14px 18px 20px
        }

        /* FullCalendar overrides */
        .fc {
            font-family: 'Inter', sans-serif;
            font-size: 12.5px
        }

        .fc .fc-toolbar {
            display: none
        }

        /* using custom toolbar */
        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: #EEF2F6
        }

        .fc .fc-col-header-cell-cushion {
            padding: 10px 6px;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: .08em;
            text-decoration: none
        }

        .fc-daygrid-day-number {
            padding: 8px 10px;
            color: #334155;
            font-weight: 600;
            text-decoration: none;
            font-size: 12.5px
        }

        .fc .fc-daygrid-day.fc-day-today {
            background: var(--grad-soft)
        }

        .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
            color: var(--primary)
        }

        .fc-daygrid-day {
            transition: .15s
        }

        .fc-daygrid-day:hover {
            background: rgba(30, 111, 120, .04)
        }

        .fc-day-other {
            opacity: .45
        }

        .fc-event {
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
            margin: 2px 4px !important;
            cursor: pointer
        }

        .evt {
            border-radius: 9px;
            padding: 6px 9px;
            color: #fff;
            font-size: 11.5px;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            gap: 2px;
            box-shadow: 0 4px 10px -4px rgba(15, 23, 42, .25);
            position: relative;
            overflow: hidden;
            transition: all .2s ease
        }

        .evt::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: rgba(255, 255, 255, .5)
        }

        .evt:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 12px 24px -10px rgba(15, 23, 42, .35);
            z-index: 5
        }

        .evt .t {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 6px
        }

        .evt .n {
            font-weight: 700;
            font-size: 11.5px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .evt .h {
            font-size: 10.5px;
            opacity: .9;
            font-weight: 500
        }

        .evt .m {
            font-size: 10px;
            opacity: .9;
            display: inline-flex;
            align-items: center;
            gap: 4px
        }

        .evt.scheduled {
            background: linear-gradient(135deg, #3B82F6, #2563EB)
        }

        .evt.sending {
            background: linear-gradient(135deg, #F59E0B, #D97706)
        }

        .evt.sent {
            background: linear-gradient(135deg, #22C55E, #16A34A)
        }

        .evt.failed {
            background: linear-gradient(135deg, #EF4444, #DC2626)
        }

        .evt.paused {
            background: linear-gradient(135deg, #94A3B8, #64748B)
        }

        /* Tooltip */
        .cal-tt {
            position: fixed;
            z-index: 1000;
            width: 280px;
            background: #fff;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            padding: 14px;
            opacity: 0;
            transform: translateY(6px);
            pointer-events: none;
            transition: .18s
        }

        .cal-tt.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto
        }

        .tt-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 3px 8px;
            border-radius: 20px;
            color: #fff
        }

        .tt-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 15px;
            font-weight: 700;
            margin: 8px 0 2px
        }

        .tt-co {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 10px
        }

        .tt-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            padding: 5px 0;
            border-top: 1px dashed var(--border)
        }

        .tt-row span:first-child {
            color: var(--muted)
        }

        .tt-row span:last-child {
            font-weight: 600
        }

        .tt-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin-top: 12px
        }

        .tt-actions button {
            border: 1px solid var(--border);
            background: #fff;
            color: #334155;
            border-radius: 9px;
            padding: 8px 0;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: .15s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px
        }

        .tt-actions button i {
            color: var(--primary);
            font-size: 12px
        }

        .tt-actions button:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--grad-soft)
        }

        .tt-actions button.danger:hover {
            color: var(--danger);
            border-color: var(--danger)
        }

        .tt-actions button.danger:hover i {
            color: var(--danger)
        }

        /* Right sidebar */
        .side {
            display: flex;
            flex-direction: column;
            gap: 16px
        }

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 16px;
            box-shadow: var(--shadow-sm)
        }

        .panel h4 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            font-weight: 700;
            margin: 0 0 12px;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .panel h4 .cnt {
            font-size: 10.5px;
            color: var(--primary);
            background: var(--grad-soft);
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600
        }

        .agenda-item {
            display: flex;
            gap: 10px;
            padding: 9px 0;
            border-top: 1px dashed var(--border);
            align-items: flex-start
        }

        .agenda-item:first-of-type {
            border-top: none
        }

        .agenda-time {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            color: var(--primary);
            font-size: 12px;
            width: 52px;
            flex-shrink: 0;
            line-height: 1.3
        }

        .agenda-time small {
            display: block;
            color: var(--muted);
            font-weight: 500;
            font-size: 10px;
            text-transform: uppercase
        }

        .agenda-title {
            font-size: 12.5px;
            font-weight: 600;
            color: #0F172A;
            line-height: 1.35
        }

        .agenda-meta {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px
        }

        .agenda-pill {
            font-size: 10px;
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            margin-top: 4px
        }

        .pill-scheduled {
            background: rgba(59, 130, 246, .12);
            color: #2563EB
        }

        .pill-sending {
            background: rgba(245, 158, 11, .15);
            color: #B45309
        }

        .pill-sent {
            background: rgba(34, 197, 94, .15);
            color: #15803D
        }

        .pill-failed {
            background: rgba(239, 68, 68, .12);
            color: #B91C1C
        }

        .pill-paused {
            background: rgba(148, 163, 184, .2);
            color: #475569
        }

        .noti {
            display: flex;
            gap: 10px;
            padding: 9px 0;
            border-top: 1px dashed var(--border)
        }

        .noti:first-of-type {
            border-top: none
        }

        .noti-ico {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            color: #fff;
            flex-shrink: 0;
            font-size: 12px
        }

        .noti-txt {
            font-size: 12px;
            line-height: 1.35
        }

        .noti-txt b {
            font-weight: 600
        }

        .noti-time {
            font-size: 10.5px;
            color: var(--muted);
            margin-top: 2px
        }

        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px
        }

        .lg {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            color: #334155;
            font-weight: 500
        }

        .lg i {
            width: 10px;
            height: 10px;
            border-radius: 3px;
            display: block
        }

        /* Drawer */
        .drawer-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .5);
            backdrop-filter: blur(4px);
            z-index: 1050;
            opacity: 0;
            pointer-events: none;
            transition: .25s
        }

        .drawer-overlay.open {
            opacity: 1;
            pointer-events: auto
        }

        .drawer {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: min(460px, 100%);
            background: #fff;
            z-index: 1060;
            box-shadow: -30px 0 60px -20px rgba(15, 23, 42, .35);
            transform: translateX(100%);
            transition: .35s cubic-bezier(.4, 0, .2, 1);
            display: flex;
            flex-direction: column
        }

        .drawer.open {
            transform: translateX(0)
        }

        .drawer-head {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            background: var(--grad);
            color: #fff;
            position: relative;
            overflow: hidden
        }

        .drawer-head::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 100% 0%, rgba(255, 255, 255, .2), transparent 60%)
        }

        .drawer-head h3 {
            position: relative;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 20px;
            font-weight: 700;
            margin: 6px 0 4px
        }

        .drawer-head p {
            position: relative;
            margin: 0;
            opacity: .9;
            font-size: 12.5px
        }

        .drawer-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .18);
            border: none;
            color: #fff;
            cursor: pointer;
            transition: .2s
        }

        .drawer-close:hover {
            background: rgba(255, 255, 255, .3);
            transform: rotate(90deg)
        }

        .drawer-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px 22px
        }

        .sec {
            margin-bottom: 18px
        }

        .sec h5 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--muted);
            font-weight: 700;
            margin: 0 0 10px
        }

        .kv {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed var(--border);
            font-size: 13px
        }

        .kv span:first-child {
            color: var(--muted)
        }

        .kv span:last-child {
            font-weight: 600;
            text-align: right;
            color: #0F172A
        }

        .msg-box {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            background: #F8FAFC;
            font-size: 12.5px;
            line-height: 1.55;
            position: relative
        }

        .msg-box::before {
            content: "WhatsApp";
            position: absolute;
            top: -9px;
            left: 14px;
            background: #25D366;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px
        }

        .stat-mini {
            background: #F8FAFC;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px;
            text-align: center
        }

        .stat-mini .v {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 18px;
            color: var(--primary)
        }

        .stat-mini .l {
            font-size: 10.5px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .06em
        }

        .drawer-foot {
            padding: 14px 22px;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 8px;
            background: #F8FAFC
        }

        .drawer-foot button {
            flex: 1
        }

        .file-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px;
            background: #F8FAFC;
            border-radius: 11px;
            margin-bottom: 6px;
            font-size: 12.5px
        }

        .file-row i {
            color: var(--primary);
            font-size: 16px
        }

        /* Modal (stepper) */
        .modal-content {
            border-radius: 22px;
            border: none;
            overflow: hidden
        }

        .modal-header {
            background: var(--grad);
            color: #fff;
            padding: 20px 24px;
            border: none;
            position: relative;
            overflow: hidden
        }

        .modal-header::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 90% 0%, rgba(255, 255, 255, .22), transparent 55%)
        }

        .modal-title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 20px
        }

        .modal-header .btn-close {
            filter: invert(1) brightness(2)
        }

        .stepper {
            display: flex;
            gap: 6px;
            padding: 20px 24px 0;
            background: #F8FAFC
        }

        .step {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
            position: relative;
            cursor: pointer
        }

        .step .dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid var(--border);
            color: var(--muted);
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 13px;
            transition: .25s;
            z-index: 1
        }

        .step .lbl {
            font-size: 11.5px;
            color: var(--muted);
            font-weight: 600
        }

        .step.active .dot {
            background: var(--grad);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 8px 18px -8px rgba(30, 111, 120, .6)
        }

        .step.active .lbl {
            color: var(--primary)
        }

        .step.done .dot {
            background: var(--success);
            border-color: transparent;
            color: #fff
        }

        .step-bar {
            position: absolute;
            top: 15px;
            left: calc(50% + 20px);
            right: calc(-50% + 20px);
            height: 2px;
            background: var(--border);
            z-index: 0
        }

        .step.done .step-bar {
            background: var(--success)
        }

        .step:last-child .step-bar {
            display: none
        }

        .step-body {
            padding: 22px 24px
        }

        .step-pane {
            display: none;
            animation: fadeUp .35s ease
        }

        .step-pane.active {
            display: block
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .05em
        }

        .form-control,
        .form-select {
            border-radius: 11px;
            border: 1px solid var(--border);
            padding: 10px 12px;
            font-size: 13.5px;
            transition: .2s
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(30, 111, 120, .12)
        }

        .modal-footer {
            border-top: 1px solid var(--border);
            padding: 14px 24px;
            background: #F8FAFC
        }

        .tpl-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 12px;
            cursor: pointer;
            transition: .2s
        }

        .tpl-card:hover,
        .tpl-card.selected {
            border-color: var(--primary);
            background: var(--grad-soft);
            transform: translateY(-2px)
        }

        .tpl-card .b {
            font-weight: 700;
            font-size: 13px
        }

        .tpl-card .p {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
            line-height: 1.4
        }

        /* Anim */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .fade-in {
            animation: fadeUp .5s ease both
        }

        .stagger>* {
            animation: fadeUp .5s ease both
        }

        .stagger>*:nth-child(1) {
            animation-delay: .05s
        }

        .stagger>*:nth-child(2) {
            animation-delay: .12s
        }

        .stagger>*:nth-child(3) {
            animation-delay: .19s
        }

        .stagger>*:nth-child(4) {
            animation-delay: .26s
        }

        .stagger>*:nth-child(5) {
            animation-delay: .33s
        }

        /* Skeleton */
        .sk {
            background: linear-gradient(90deg, #EEF2F6 0%, #F8FAFC 50%, #EEF2F6 100%);
            background-size: 200% 100%;
            border-radius: 8px;
            animation: shine 1.4s linear infinite
        }

        @keyframes shine {
            0% {
                background-position: 200% 0
            }

            100% {
                background-position: -200% 0
            }
        }

        /* Responsive */
        @media (max-width:1200px) {
            .work {
                grid-template-columns: 1fr
            }

            .side {
                order: 2
            }

            .kpi-grid {
                grid-template-columns: repeat(2, 1fr)
            }

            .filters {
                grid-template-columns: repeat(3, 1fr) auto
            }
        }

        @media (max-width:768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: .3s;
                z-index: 1100
            }

            .sidebar.open {
                transform: translateX(0)
            }

            .main {
                margin-left: 0;
                padding: 16px
            }

            .filters {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px
            }

            .btn-clear {
                grid-column: span 2
            }

            .cal-toolbar {
                padding: 12px 14px
            }

            .cal-views {
                width: 100%;
                justify-content: space-between
            }

            .cal-views button {
                flex: 1;
                padding: 8px 6px;
                font-size: 11.5px
            }

            .page-head h1 {
                font-size: 22px
            }

            .drawer {
                width: 100%
            }

            .stepper {
                overflow-x: auto;
                padding: 14px
            }

            .step .lbl {
                font-size: 10.5px
            }
        }
    </style>
@endsection

@section('content')
    <main>

        <!-- KPI -->
        <div class="kpi-grid">
            <div class="kpi">
                <div class="kpi-inner">
                    <div>
                        <div class="kpi-label">{{ __('Campañas Programadas') }}</div>
                        <div class="kpi-value" data-count="42">0</div>
                        <div class="kpi-delta"><i class="fa-solid fa-arrow-trend-up"></i>{{ __('+12% vs semana pasada') }}</div>
                    </div>
                    <div class="kpi-ico"><i class="fa-solid fa-calendar-plus"></i></div>
                </div>
                <svg class="kpi-spark" viewBox="0 0 200 26" preserveAspectRatio="none">
                    <path d="M0,20 L30,15 L60,18 L90,10 L120,14 L150,6 L180,10 L200,4" fill="none" stroke="#1E6F78"
                        stroke-width="2" />
                </svg>
            </div>
            <div class="kpi">
                <div class="kpi-inner">
                    <div>
                        <div class="kpi-label">{{ __('Campañas Enviadas') }}</div>
                        <div class="kpi-value" data-count="128">0</div>
                        <div class="kpi-delta"><i class="fa-solid fa-arrow-trend-up"></i>{{ __('+8% este mes') }}</div>
                    </div>
                    <div class="kpi-ico ok"><i class="fa-solid fa-paper-plane"></i></div>
                </div>
                <svg class="kpi-spark" viewBox="0 0 200 26" preserveAspectRatio="none">
                    <path d="M0,22 L30,18 L60,20 L90,12 L120,16 L150,8 L180,6 L200,3" fill="none" stroke="#22C55E"
                        stroke-width="2" />
                </svg>
            </div>
            <div class="kpi">
                <div class="kpi-inner">
                    <div>
                        <div class="kpi-label">{{ __('Mensajes Pendientes') }}</div>
                        <div class="kpi-value" data-count="3450">0</div>
                        <div class="kpi-delta down"><i class="fa-solid fa-arrow-trend-down"></i>{{ __('-3% hoy') }}</div>
                    </div>
                    <div class="kpi-ico warn"><i class="fa-solid fa-hourglass-half"></i></div>
                </div>
                <svg class="kpi-spark" viewBox="0 0 200 26" preserveAspectRatio="none">
                    <path d="M0,10 L30,14 L60,8 L90,16 L120,10 L150,14 L180,18 L200,20" fill="none" stroke="#F59E0B"
                        stroke-width="2" />
                </svg>
            </div>
            <div class="kpi">
                <div class="kpi-inner">
                    <div>
                        <div class="kpi-label">{{ __('Tasa de Entrega') }}</div>
                        <div class="kpi-value"><span data-count="97">0</span>.4%</div>
                        <div class="kpi-delta"><i class="fa-solid fa-arrow-trend-up"></i>{{ __('+1.2% este mes') }}</div>
                    </div>
                    <div class="kpi-ico dark"><i class="fa-solid fa-circle-check"></i></div>
                </div>
                <svg class="kpi-spark" viewBox="0 0 200 26" preserveAspectRatio="none">
                    <path d="M0,18 L30,14 L60,16 L90,10 L120,12 L150,6 L180,8 L200,4" fill="none" stroke="#145962"
                        stroke-width="2" />
                </svg>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="filter"><label>{{ __('Empresa') }}</label>
                <select>
                    <option>{{ __('Todas') }}</option>
                    <option>{{ __('Coca-Cola LATAM') }}</option>
                    <option>{{ __('Nestlé Perú') }}</option>
                    <option>{{ __('Movistar') }}</option>
                    <option>{{ __('Farmacias Cruz Verde') }}</option>
                </select>
            </div>
            <div class="filter"><label>{{ __('Etiqueta') }}</label>
                <select>
                    <option>{{ __('Todas') }}</option>
                    <option>{{ __('Promocional') }}</option>
                    <option>{{ __('Transaccional') }}</option>
                    <option>{{ __('Recordatorio') }}</option>
                    <option>{{ __('Encuesta') }}</option>
                </select>
            </div>
            <div class="filter"><label>{{ __('Estado') }}</label>
                <select>
                    <option>{{ __('Todos') }}</option>
                    <option>{{ __('Programada') }}</option>
                    <option>{{ __('Enviando') }}</option>
                    <option>{{ __('Enviada') }}</option>
                    <option>{{ __('Fallida') }}</option>
                    <option>{{ __('Pausada') }}</option>
                </select>
            </div>
            <div class="filter"><label>{{ __('Canal') }}</label>
                <select>
                    <option>{{ __('WhatsApp Business') }}</option>
                    <option>{{ __('WhatsApp Cloud API') }}</option>
                    <option>{{ __('SMS Backup') }}</option>
                </select>
            </div>
            <div class="filter"><label>{{ __('Fecha') }}</label>
                <input type="date" />
            </div>
            <div class="filter"><label>{{ __('Responsable') }}</label>
                <select>
                    <option>{{ __('Todos') }}</option>
                    <option>{{ __('Julio García') }}</option>
                    <option>{{ __('Ana Ríos') }}</option>
                    <option>{{ __('Marco Peña') }}</option>
                </select>
            </div>
            <button class="btn-clear"><i class="fa-solid fa-filter"></i>{{ __('Aplicar') }}</button>
        </div>

        <!-- Work -->
        <div class="work">
            <div class="cal-card">
                <div class="cal-toolbar">
                    <div class="cal-title"><i class="fa-solid fa-calendar-days" style="color:var(--primary)"></i><span
                            id="calTitle">—</span></div>
                    <div class="cal-nav">
                        <button id="prev" title="{{ __('Anterior') }}"><i class="fa-solid fa-chevron-left"></i></button>
                        <button id="today" class="today">{{ __('Hoy') }}</button>
                        <button id="next" title="{{ __('Siguiente') }}"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                    <div class="cal-views">
                        <button data-view="dayGridMonth" class="active">{{ __('Mes') }}</button>
                        <button data-view="timeGridWeek">{{ __('Semana') }}</button>
                        <button data-view="timeGridDay">{{ __('Día') }}</button>
                        <button data-view="listWeek">{{ __('Lista') }}</button>
                    </div>
                </div>
                <div id="calendar"></div>
            </div>

            <!-- Right sidebar -->
            <aside class="side stagger">
                <div class="panel">
                    <h4><span><i class="fa-solid fa-bolt" style="color:var(--warning)"></i>{{ __('Hoy') }}</span><span
                            class="cnt" id="todayCount">0</span></h4>
                    <div id="todayAgenda"></div>
                </div>
                <div class="panel">
                    <h4><span><i class="fa-solid fa-forward" style="color:var(--primary)"></i>{{ __('Próximas') }}</span><span
                            class="cnt" id="upcomingCount">0</span></h4>
                    <div id="upcomingAgenda"></div>
                </div>
                <div class="panel d-none">
                    <h4><span><i class="fa-regular fa-bell" style="color:var(--danger)"></i>{{ __('Notificaciones') }}</span>
                    </h4>
                    <div class="noti">
                        <div class="noti-ico" style="background:linear-gradient(135deg,#22C55E,#16A34A)"><i
                                class="fa-solid fa-check"></i></div>
                        <div class="noti-txt"><b>{{ __('Coca-Cola LATAM') }}</b>{{ __('completó envío a 12,540 contactos.') }}<div
                                class="noti-time">{{ __('hace 12 min') }}</div>
                        </div>
                    </div>
                    <div class="noti">
                        <div class="noti-ico" style="background:linear-gradient(135deg,#F59E0B,#D97706)"><i
                                class="fa-solid fa-triangle-exclamation"></i></div>
                        <div class="noti-txt"><b>{{ __('Nestlé Perú') }}</b>{{ __('tiene 34 mensajes fallidos por reintento.') }}<div
                                class="noti-time">{{ __('hace 34 min') }}</div>
                        </div>
                    </div>
                    <div class="noti">
                        <div class="noti-ico" style="background:linear-gradient(135deg,#3B82F6,#2563EB)"><i
                                class="fa-solid fa-calendar-plus"></i></div>
                        <div class="noti-txt">{{ __('Nueva campaña') }}<b>{{ __('Black Friday 2026') }}</b>{{ __('programada para el 27/11.') }}<div
                                class="noti-time">{{ __('hace 1 h') }}</div>
                        </div>
                    </div>
                    <div class="noti">
                        <div class="noti-ico" style="background:linear-gradient(135deg,#8B5CF6,#7C3AED)"><i
                                class="fa-solid fa-robot"></i></div>
                        <div class="noti-txt">{{ __('IA optimizó horario de envío:') }}<b>{{ __('+18% aperturas') }}</b>{{ __('estimadas.') }}<div
                                class="noti-time">{{ __('hace 2 h') }}</div>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <h4><span><i class="fa-solid fa-palette" style="color:var(--accent)"></i>{{ __('Estados') }}</span></h4>
                    <div class="legend">
                        <div class="lg"><i style="background:#3B82F6"></i>{{ __('Programada') }}</div>
                        <div class="lg"><i style="background:#F59E0B"></i>{{ __('Enviando') }}</div>
                        <div class="lg"><i style="background:#22C55E"></i>{{ __('Enviada') }}</div>
                        <div class="lg"><i style="background:#EF4444"></i>{{ __('Fallida') }}</div>
                        <div class="lg"><i style="background:#94A3B8"></i>{{ __('Pausada') }}</div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- Tooltip -->
    <div class="cal-tt" id="tooltip">
        <span class="tt-badge" id="ttBadge">{{ __('Programada') }}</span>
        <div class="tt-name" id="ttName">—</div>
        <div class="tt-co" id="ttCo">—</div>
        <div class="tt-row"><span>{{ __('Fecha') }}</span><span id="ttDate">—</span></div>
        <div class="tt-row"><span>{{ __('Mensajes') }}</span><span id="ttMsgs">—</span></div>
        <div class="tt-row"><span>{{ __('Audiencia estimada') }}</span><span id="ttAud">—</span></div>
        <div class="tt-row"><span>{{ __('Responsable') }}</span><span id="ttOwner">—</span></div>
        <div class="tt-actions">
            <button onclick="openDrawer(currentEvent)"><i class="fa-solid fa-eye"></i>{{ __('Ver') }}</button>
            <button><i class="fa-solid fa-pen"></i>{{ __('Editar') }}</button>
            <button><i class="fa-solid fa-clone"></i>{{ __('Duplicar') }}</button>
            <button class="danger"><i class="fa-solid fa-ban"></i>{{ __('Cancelar') }}</button>
        </div>
    </div>

    <!-- Drawer -->
    <div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
    <aside class="drawer" id="drawer" aria-hidden="true">
        <div class="drawer-head">
            <button class="drawer-close" onclick="closeDrawer()"><i class="fa-solid fa-xmark"></i></button>
            <span class="tt-badge" id="dBadge" style="background:rgba(255,255,255,.25)">{{ __('Programada') }}</span>
            <h3 id="dName">{{ __('Campaña') }}</h3>
            <p id="dCo">{{ __('Empresa') }}</p>
        </div>
        <div class="drawer-body">
            <div class="sec">
                <h5>{{ __('Detalles de la Campaña') }}</h5>
                <div class="kv"><span>{{ __('Fecha programada') }}</span><span id="dDate">—</span></div>
                <div class="kv"><span>{{ __('Canal') }}</span><span>{{ __('WhatsApp Cloud API') }}</span></div>
                <div class="kv"><span>{{ __('Plantilla') }}</span><span id="dTpl">{{ __('promo_black_friday_v3') }}</span></div>
                <div class="kv"><span>{{ __('Responsable') }}</span><span id="dOwner">—</span></div>
                <div class="kv"><span>{{ __('Segmento') }}</span><span id="dSeg">{{ __('Clientes VIP · LATAM') }}</span></div>
            </div>

            <div class="sec">
                <h5>{{ __('Vista Previa del Mensaje') }}</h5>
                <div class="msg-box">{{ __('Hola { nombre } 👋, tenemos una oferta exclusiva para ti:') }}<b>{{ __('50% de descuento') }}</b>{{ __('en toda
                    la tienda durante 24 horas. Usa el código') }<b>{{ __('VIP50') }}</b>{{ __('antes de que termine. Ver catálogo →
                    gijac.co/promo') }</div>
            </div>

            <div class="sec">
                <h5>{{ __('Estadísticas de Entrega') }}</h5>
                <div class="stat-grid">
                    <div class="stat-mini">
                        <div class="v" id="sMsgs">—</div>
                        <div class="l">{{ __('Mensajes') }}</div>
                    </div>
                    <div class="stat-mini">
                        <div class="v" id="sDel">96%</div>
                        <div class="l">{{ __('Entregados') }}</div>
                    </div>
                    <div class="stat-mini">
                        <div class="v" id="sOpen">72%</div>
                        <div class="l">{{ __('Aperturas') }}</div>
                    </div>
                </div>
            </div>

            <div class="sec">
                <h5>{{ __('Archivos & Adjuntos') }}</h5>
                <div class="file-row"><i class="fa-solid fa-image"></i>
                    <div>{{ __('banner-black-friday.jpg') }}<div style="font-size:11px;color:var(--muted)">{{ __('1.2 MB · Imagen') }}</div>
                    </div>
                </div>
                <div class="file-row"><i class="fa-solid fa-file-pdf"></i>
                    <div>{{ __('catalogo-vip.pdf') }}<div style="font-size:11px;color:var(--muted)">{{ __('3.8 MB · PDF') }}</div>
                    </div>
                </div>
            </div>

            <div class="sec">
                <h5>{{ __('Destinatarios') }}</h5>
                <div class="kv"><span>{{ __('Audiencia estimada') }}</span><span id="dAud">—</span></div>
                <div class="kv"><span>{{ __('Excluidos') }}</span><span>{{ __('842 contactos (opt-out)') }}</span></div>
                <div class="kv"><span>{{ __('Lista') }}</span><span>{{ __('vip_latam_q4_2026.csv') }}</span></div>
            </div>
        </div>
        <div class="drawer-foot">
            <button class="btn-ghost"><i class="fa-solid fa-pen"></i>{{ __('Editar') }}</button>
            <button class="btn-ghost"><i class="fa-solid fa-clone"></i>{{ __('Duplicar') }}</button>
            <button class="btn-brand" style="flex:1.4"><i class="fa-solid fa-paper-plane"></i>{{ __('Enviar ahora') }}</button>
        </div>
    </aside>
@endsection

@section('modal')
@endsection

@section('scripts')
    <script>
        /* Particles */
        $(function() {
            const $p = $('#particles');
            for (let i = 0; i < 28; i++) {
                const dur = 12 + Math.random() * 18,
                    delay = -Math.random() * 20,
                    size = 3 + Math.random() * 4;
                $('<span class="particle">{{ __('\') }}.css({
                    left: Math.random() * 100 + \'%\',
                    bottom: -20 + \'px\',
                    width: size,
                    height: size,
                    background: `rgba(30,111,120,${.15+Math.random()*.35})`,
                    animationDuration: dur + \'s\',
                    animationDelay: delay + \'s\'
                }).appendTo($p);
            }

            /* Counter animations */
            $(\'[data-count]\').each(function() {
                const $el = $(this),
                    target = parseInt($el.data(\'count\'), 10);
                $({
                    v: 0
                }).animate({
                    v: target
                }, {
                    duration: 1400,
                    easing: \'swing\',
                    step: function(now) {
                        $el.text(Math.floor(now).toLocaleString(\'es-PE\'));
                    },
                    complete: function() {
                        $el.text(target.toLocaleString(\'es-PE\'));
                    }
                });
            });
        });

        /* Sample data */
        const owners = [\'Julio García\', \'Ana Ríos\', \'Marco Peña\', \'Lucía Torres\'];
        const companies = [\'Coca-Cola LATAM\', \'Nestlé Perú\', \'Movistar\', \'Farmacias Cruz Verde\', \'BBVA Continental\',
            \'Rappi Perú\'
        ];
        const names = [\'Black Friday 2026\', \'Onboarding VIP\', \'Recordatorio Cita\', \'Encuesta NPS Q4\',
            \'Reactivación Clientes\', \'Lanzamiento Producto\', \'Cyber Wow\', \'Feliz Cumpleaños\', \'Alerta de Envío\',
            \'Promo Fin de Semana\', \'Ronda de Fidelidad\', \'Recuperación Carrito\'
        ];
        const statuses = [\'scheduled\', \'sending\', \'sent\', \'failed\', \'paused\'];
        const statusLabels = {
            scheduled: \'Programada\',
            sending: \'Enviando\',
            sent: \'Enviada\',
            failed: \'Fallida\',
            paused: \'Pausada\'
        };

        function rand(a) {
            return a[Math.floor(Math.random() * a.length)]
        }

        function pad(n) {
            return n') }< 10 ? '0' + n : n
        }

        const events = [];
        const today = new Date();
        for (let i = 0; i < 26; i++) {
            const offset = Math.floor(Math.random() * 45) - 15;
            const d = new Date(today);
            d.setDate(today.getDate() + offset);
            const hour = 8 + Math.floor(Math.random() * 11);
            d.setHours(hour, Math.random() < .5 ? 0 : 30, 0, 0);
            const status = d < today ? rand(['sent', 'sent', 'sent', 'failed', 'paused']) : rand(['scheduled', 'scheduled',
                'sending', 'paused'
            ]);
            const audience = 500 + Math.floor(Math.random() * 20000);
            events.push({
                id: 'evt-' + i,
                title: rand(names),
                start: d.toISOString(),
                end: new Date(d.getTime() + 45 * 60000).toISOString(),
                extendedProps: {
                    status,
                    audience,
                    messages: audience,
                    company: rand(companies),
                    owner: rand(owners),
                    template: rand(['promo_black_friday_v3', 'recordatorio_cita_v2', 'encuesta_nps_v1',
                        'bienvenida_vip_v1'
                    ])
                }
            });
        }

        /* Calendar */
        let calendar, currentEvent = null;
        document.addEventListener('DOMContentLoaded', () =>{{ __('{
            const el = document.getElementById(\'calendar\');
            calendar = new FullCalendar.Calendar(el, {
                initialView: \'dayGridMonth\',
                locale: \'es\',
                width: \'100%\',
                height: \'auto\',
                firstDay: 1,
                editable: true,
                droppable: true,
                eventResizableFromStart: true,
                headerToolbar: false,
                dayMaxEvents: 3,
                events,
                eventContent(arg) {
                    const p = arg.event.extendedProps;
                    const time = arg.timeText || arg.event.start.toLocaleTimeString(\'es-PE\', {
                        hour: \'2-digit\',
                        minute: \'2-digit\'
                    });
                    const aud = p.audience.toLocaleString(\'es-PE\');
                    const el = document.createElement(\'div\');
                    el.className = \'evt \' + p.status;
                    el.innerHTML = `') }<div class="t"><span class="n">{{ __('${arg.event.title}') }}</span><span class="h">{{ __('${time}') }}</span></div>
                        <span class="m"><i class="fa-solid fa-users"></i>{{ __('${aud}') }}</span>{{ __('`;
                    return {
                        domNodes: [el]
                    };
                },
                eventMouseEnter(info) {
                    showTooltip(info);
                },
                eventMouseLeave() {
                    hideTooltip();
                },
                eventClick(info) {
                    info.jsEvent.preventDefault();
                    openDrawer(info.event);
                },
                datesSet(info) {
                    document.getElementById(\'calTitle\').textContent = info.view.title.replace(/^\w/, c => c
                        .toUpperCase());
                }
            });
            calendar.render();

            document.getElementById(\'prev\').onclick = () => calendar.prev();
            document.getElementById(\'next\').onclick = () => calendar.next();
            document.getElementById(\'today\').onclick = () => calendar.today();
            document.querySelectorAll(\'.cal-views button\').forEach(b => {
                b.onclick = () => {
                    document.querySelectorAll(\'.cal-views button\').forEach(x => x.classList.remove(
                        \'active\'));
                    b.classList.add(\'active\');
                    calendar.changeView(b.dataset.view);
                };
            });

            renderAgenda();
        });

        /* Tooltip */
        function showTooltip(info) {
            currentEvent = info.event;
            const p = info.event.extendedProps;
            const badge = document.getElementById(\'ttBadge\');
            const colors = {
                scheduled: \'#3B82F6\',
                sending: \'#F59E0B\',
                sent: \'#22C55E\',
                failed: \'#EF4444\',
                paused: \'#94A3B8\'
            };
            badge.style.background = colors[p.status];
            badge.textContent = statusLabels[p.status];
            document.getElementById(\'ttName\').textContent = info.event.title;
            document.getElementById(\'ttCo\').textContent = p.company;
            document.getElementById(\'ttDate\').textContent = info.event.start.toLocaleString(\'es-PE\', {
                dateStyle: \'medium\',
                timeStyle: \'short\'
            });
            document.getElementById(\'ttMsgs\').textContent = p.messages.toLocaleString(\'es-PE\');
            document.getElementById(\'ttAud\').textContent = p.audience.toLocaleString(\'es-PE\');
            document.getElementById(\'ttOwner\').textContent = p.owner;

            const tt = document.getElementById(\'tooltip\');
            const r = info.el.getBoundingClientRect();
            let left = r.right + 12,
                top = r.top - 8;
            if (left + 300 > window.innerWidth) left = r.left - 292;
            if (top + 320 > window.innerHeight) top = window.innerHeight - 340;
            if (top') }< 8) top = 8;
            tt.style.left = left + 'px';
            tt.style.top = top + 'px';
            tt.classList.add('show');
        }

        function hideTooltip() {
            setTimeout(() =>{{ __('{
                if (!document.getElementById(\'tooltip\').matches(\':hover\')) document.getElementById(\'tooltip\')
                    .classList.remove(\'show\');
            }, 120);
        }
        document.getElementById(\'tooltip\').addEventListener(\'mouseleave\', () => document.getElementById(\'tooltip\').classList
            .remove(\'show\'));

        /* Drawer */
        function openDrawer(evt) {
            if (!evt) return;
            const p = evt.extendedProps;
            document.getElementById(\'dName\').textContent = evt.title;
            document.getElementById(\'dCo\').textContent = p.company + \' · \' + p.template;
            document.getElementById(\'dBadge\').textContent = statusLabels[p.status];
            document.getElementById(\'dDate\').textContent = evt.start.toLocaleString(\'es-PE\', {
                dateStyle: \'long\',
                timeStyle: \'short\'
            });
            document.getElementById(\'dTpl\').textContent = p.template;
            document.getElementById(\'dOwner\').textContent = p.owner;
            document.getElementById(\'dAud\').textContent = p.audience.toLocaleString(\'es-PE\');
            document.getElementById(\'sMsgs\').textContent = p.messages.toLocaleString(\'es-PE\');
            document.getElementById(\'drawer\').classList.add(\'open\');
            document.getElementById(\'drawerOverlay\').classList.add(\'open\');
            document.getElementById(\'tooltip\').classList.remove(\'show\');
        }

        function closeDrawer() {
            document.getElementById(\'drawer\').classList.remove(\'open\');
            document.getElementById(\'drawerOverlay\').classList.remove(\'open\');
        }

        /* Agenda */
        function renderAgenda() {
            const now = new Date();
            const startToday = new Date(now);
            startToday.setHours(0, 0, 0, 0);
            const endToday = new Date(now);
            endToday.setHours(23, 59, 59, 999);
            const t = events.filter(e => new Date(e.start) >= startToday && new Date(e.start)') }<= endToday)
                .sort((a, b) =>{{ __('new Date(a.start) - new Date(b.start));
            const up = events.filter(e => new Date(e.start) > endToday)
                .sort((a, b) => new Date(a.start) - new Date(b.start)).slice(0, 5);

            const fmt = (d) => {
                const dt = new Date(d);
                return {
                    h: dt.toLocaleTimeString(\'es-PE\', {
                        hour: \'2-digit\',
                        minute: \'2-digit\'
                    }),
                    d: dt.toLocaleDateString(\'es-PE\', {
                        day: \'2-digit\',
                        month: \'short\'
                    })
                };
            };

            const renderList = (list, empty) => list.length ? list.map(e => {
                    const f = fmt(e.start);
                    return `') }<div class="agenda-item">
                                <div class="agenda-time">{{ __('${f.h}') }}<small>{{ __('${f.d}') }}</small></div>
                                <div>
                                    <div class="agenda-title">{{ __('${e.title}') }}</div>
                                    <div class="agenda-meta">{{ __('${e.extendedProps.company} · ${e.extendedProps.audience.toLocaleString(\\'es-PE\') }}contactos') }</div>
                                    <span class="agenda-pill pill-${e.extendedProps.status}">{{ __('${statusLabels[e.extendedProps.status]}') }}</span>
                                </div>
                            </div>{{ __('`;
                }).join(\'\') :
                `') }<div style="text-align:center;padding:14px 0;color:var(--muted);font-size:12.5px"><i class="fa-regular fa-calendar" style="font-size:22px;display:block;margin-bottom:6px;color:var(--primary);opacity:.5"></i>{{ __('${empty}') }}</div>{{ __('`;

            document.getElementById(\'todayAgenda\').innerHTML = renderList(t, \'Sin campañas hoy\');
            document.getElementById(\'upcomingAgenda\').innerHTML = renderList(up, \'Sin campañas próximas\');
            document.getElementById(\'todayCount\').textContent = t.length;
            document.getElementById(\'upcomingCount\').textContent = up.length;
        }

        /* Stepper */
        let step = 1;
        const total = 5;

        function setStep(n) {
            step = Math.max(1, Math.min(total, n));
            document.querySelectorAll(\'#stepper .step\').forEach(s => {
                const i = parseInt(s.dataset.step, 10);
                s.classList.toggle(\'active\', i === step);
                s.classList.toggle(\'done\', i') }< step);
            });
            document.querySelectorAll('.step-pane').forEach(p =>{{ __('{
                p.classList.toggle(\'active\', parseInt(p.dataset.pane, 10) === step);
            });
            document.getElementById(\'btnPrev\').disabled = step === 1;
            document.getElementById(\'btnNext\').innerHTML = step === total ?
                \'') }<i class="fa-solid fa-check"></i>{{ __('Programar campaña\\' : \\'Siguiente') }}<i class="fa-solid fa-arrow-right"></i>{{ __('\';
        }
        document.getElementById(\'btnNext\').onclick = () => {
            if (step === total) {
                bootstrap.Modal.getInstance(document.getElementById(\'modalCampaign\')).hide();
                setTimeout(() => setStep(1), 400);
                return;
            }
            setStep(step + 1);
        };
        document.getElementById(\'btnPrev\').onclick = () => setStep(step - 1);
        document.querySelectorAll(\'#stepper .step\').forEach(s => s.onclick = () => setStep(parseInt(s.dataset.step, 10)));
        document.querySelectorAll(\'.tpl-card\').forEach(c => c.onclick = () => {
            document.querySelectorAll(\'.tpl-card\').forEach(x => x.classList.remove(\'selected\'));
            c.classList.add(\'selected\');
        });

        /* ESC closes drawer */
        document.addEventListener(\'keydown\', e => {
            if (e.key === \'Escape\') closeDrawer();
        });') }</script>
@endsection
