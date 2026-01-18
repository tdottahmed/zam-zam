<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Asia - Invoice Generators</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-green: #2e8b57; /* Standard branding green */
            --brand-orange: #ff8c00; /* Standard branding orange */
            --brand-light: #ccffcc;
            --dark-bg: #111827;
            --card-bg: rgba(255, 255, 255, 0.95);
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            color: #333;
        }

        /* Decorative background elements */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.6;
            z-index: 1;
        }
        .orb-1 {
            width: 400px;
            height: 400px;
            background: var(--brand-green);
            top: -100px;
            left: -100px;
        }
        .orb-2 {
            width: 300px;
            height: 300px;
            background: var(--brand-orange);
            bottom: -50px;
            right: -50px;
        }

        .container {
            position: relative;
            z-index: 10;
            background: var(--card-bg);
            padding: 3rem 4rem;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 450px;
            width: 90%;
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }
        
        .container:hover {
            transform: translateY(-5px);
        }

        .logo-container {
            margin-bottom: 2rem;
            position: relative;
        }
        
        .logo-container img {
            height: 80px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--brand-green);
            margin: 0;
            letter-spacing: -0.5px;
        }

        p.slogan {
            font-size: 1.1rem;
            color: #666;
            margin-top: 0.5rem;
            margin-bottom: 2.5rem;
            font-weight: 400;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(90deg, var(--brand-green) 0%, #3cb371 100%);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 10px 20px rgba(46, 139, 87, 0.3);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 25px rgba(46, 139, 87, 0.4);
            background: linear-gradient(90deg, var(--brand-orange) 0%, #ffae42 100%);
        }
        
        .btn:active {
            transform: scale(0.95);
        }

        .footer {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('images/logo.jpg') }}" alt="Super Asia ZIE Logo">
        </div>
        
        <h1>Invoice Generator</h1>
        <p class="slogan">Flavors of the World</p>
        
        <a href="/invoice-pdf" class="btn" target="_blank">
             Preview Invoice PDF
        </a>

        <div class="footer">
            &copy; {{ date('Y') }} Super Asia Foods. All Rights Reserved.
        </div>
    </div>
</body>
</html>
