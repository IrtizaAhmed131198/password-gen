<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Password Generator - Secure & Free</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- SEO --}}
    <meta name="description" content="Free secure password generator built with Laravel. Create strong, random, unbreakable passwords instantly.">
    <meta property="og:title" content="Password Generator - Secure & Free">
    <meta property="og:description" content="Protect your accounts with strong, random passwords. Generate instantly, no tracking.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('favicon.png') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">

    <!-- Navigation -->
    <nav class="fixed w-full bg-white shadow z-50">
        <div class="max-w-6xl mx-auto px-6 flex justify-between items-center h-16">
            <a href="#home" class="text-xl font-bold text-indigo-600">🔐 SecurePass</a>
            <div class="space-x-6 text-gray-700 font-medium hidden sm:flex">
                <a href="#home" class="hover:text-indigo-600">Home</a>
                <a href="#generator" class="hover:text-indigo-600">Generator</a>
                <a href="#about" class="hover:text-indigo-600">About</a>
                <a href="#faq" class="hover:text-indigo-600">FAQ</a>
                <a href="#blog" class="hover:text-indigo-600">Blog</a>
                <a href="#contact" class="hover:text-indigo-600">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center pt-32 pb-20">
        <h1 class="text-4xl sm:text-5xl font-bold mb-4">Generate Strong & Secure Passwords</h1>
        <p class="text-lg sm:text-xl text-indigo-100 max-w-2xl mx-auto">
            Protect your online accounts with randomly generated, unbreakable passwords.
            No storage, no tracking — everything is generated locally.
        </p>
        <a href="#generator" class="mt-6 inline-block bg-white text-indigo-600 font-semibold px-6 py-3 rounded-xl shadow hover:bg-gray-100">
            Try Generator
        </a>
    </section>

    <!-- Password Generator -->
    <section id="generator" class="max-w-3xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-bold text-center mb-8">🔑 Password Generator</h2>
        <div class="bg-white shadow-xl rounded-2xl p-8">

            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-red-50 p-4 text-red-700">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.generate') }}" class="grid gap-6">
                @csrf

                <!-- Password Length -->
                <div>
                    <label for="length" class="block font-medium">Password Length</label>
                    <input type="number" id="length" name="length" min="8" max="128"
                           value="{{ old('length', $options['length']) }}"
                           class="w-40 mt-2 rounded-xl border px-3 py-2">
                    <p class="text-sm text-gray-600">Choose between 8 – 128 characters</p>
                </div>

                <!-- Options -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @php
                        $checks = [
                            'uppercase' => 'Include Uppercase (A–Z)',
                            'lowercase' => 'Include Lowercase (a–z)',
                            'numbers'   => 'Include Numbers (0–9)',
                            'symbols'   => 'Include Symbols (!@#...)',
                            'avoid_ambiguous' => 'Avoid Ambiguous (0/O/1/l)',
                        ];
                    @endphp

                    @foreach($checks as $name => $label)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="{{ $name }}" value="1"
                                   @checked(old($name, $options[$name]))>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button class="flex-1 rounded-2xl bg-indigo-600 px-5 py-3 text-white font-semibold hover:bg-indigo-700">
                        Generate Password
                    </button>

                    <a href="{{ route('home') }}"
                       class="rounded-2xl bg-gray-200 px-5 py-3 font-semibold hover:bg-gray-300 text-gray-800">
                        Reset
                    </a>
                </div>
            </form>

            <!-- Result -->
            @if($result)
                <div class="mt-8 border-t pt-6">
                    <h3 class="font-semibold text-lg mb-2">Generated Password</h3>
                    <div class="flex gap-2">
                        <input id="pw" type="text" readonly
                               value="{{ $result }}"
                               class="flex-1 rounded-xl border px-3 py-2 font-mono text-lg">
                        <button id="copyBtn"
                                class="rounded-xl border px-4 py-2 font-medium hover:bg-gray-50">
                            Copy
                        </button>
                    </div>

                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-1">Strength (est.)</p>
                        <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                            <div id="meter" class="h-3 rounded-full"></div>
                        </div>
                        <div id="meterText" class="text-sm mt-1 text-gray-700"></div>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="bg-gray-100 py-16 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-4">ℹ️ About This Tool</h2>
            <p class="text-lg text-gray-700">
                SecurePass is a free, open-source password generator built with Laravel.
                It uses cryptographically secure random generation, ensuring your passwords
                are strong, unique, and impossible to guess.
            </p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-16 px-6 bg-white">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">❓ Frequently Asked Questions</h2>

            <div class="space-y-6">
                <div class="p-5 bg-gray-50 rounded-xl shadow-sm">
                    <h3 class="font-semibold text-lg mb-2">Is this password generator safe?</h3>
                    <p class="text-gray-700">Yes! All passwords are generated instantly in your browser using secure methods. Nothing is stored or sent to our servers.</p>
                </div>
                <div class="p-5 bg-gray-50 rounded-xl shadow-sm">
                    <h3 class="font-semibold text-lg mb-2">Can I use these passwords for banking or email?</h3>
                    <p class="text-gray-700">Absolutely. Generated passwords are strong enough for banking, email, and any online accounts. We recommend storing them in a trusted password manager.</p>
                </div>
                <div class="p-5 bg-gray-50 rounded-xl shadow-sm">
                    <h3 class="font-semibold text-lg mb-2">What length is best for security?</h3>
                    <p class="text-gray-700">For most accounts, 12–16 characters is considered strong. For highly sensitive accounts, use 20+ characters.</p>
                </div>
                <div class="p-5 bg-gray-50 rounded-xl shadow-sm">
                    <h3 class="font-semibold text-lg mb-2">Do I need to remember my password?</h3>
                    <p class="text-gray-700">It’s best to store it in a password manager so you don’t have to memorize long, complex passwords.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section id="blog" class="bg-gray-100 py-16 px-6">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">📝 Latest from Our Blog</h2>

            <div class="grid md:grid-cols-3 gap-6">
                <article class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                    <h3 class="font-semibold text-xl mb-2">Why Strong Passwords Matter</h3>
                    <p class="text-gray-700 mb-4">Weak passwords are the #1 reason for data breaches. Learn how to create and manage strong ones easily...</p>
                    <a href="#" class="text-indigo-600 font-medium hover:underline">Read more →</a>
                </article>

                <article class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                    <h3 class="font-semibold text-xl mb-2">Top 5 Password Manager Tools</h3>
                    <p class="text-gray-700 mb-4">A password generator works best with a secure manager. Here are 5 trusted tools we recommend...</p>
                    <a href="#" class="text-indigo-600 font-medium hover:underline">Read more →</a>
                </article>

                <article class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                    <h3 class="font-semibold text-xl mb-2">How Hackers Crack Passwords</h3>
                    <p class="text-gray-700 mb-4">Hackers use brute-force, dictionary, and rainbow table attacks. See why random generation protects you...</p>
                    <a href="#" class="text-indigo-600 font-medium hover:underline">Read more →</a>
                </article>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-4">📩 Contact</h2>
            <p class="text-gray-700 mb-6">Have questions, suggestions, or feedback? Reach out to us!</p>
            <a href="mailto:info@securepass.com" class="inline-block bg-indigo-600 text-white font-semibold px-6 py-3 rounded-xl shadow hover:bg-indigo-700">
                Email Us
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-6 text-gray-600 border-t">
        <p>© {{ date('Y') }} SecurePass | Built with ❤️ in Laravel</p>
    </footer>

    <script>
        (function () {
            const btn = document.getElementById('copyBtn');
            const input = document.getElementById('pw');
            if (btn && input) {
                btn.addEventListener('click', async (e) => {
                    e.preventDefault();
                    await navigator.clipboard.writeText(input.value);
                    btn.textContent = 'Copied!';
                    setTimeout(() => btn.textContent = 'Copy', 1200);
                });

                const pw = input.value || '';
                const opts = @json($options ?? []);
                let alphabet = 0;
                if (opts.uppercase) alphabet += 26;
                if (opts.lowercase) alphabet += 26;
                if (opts.numbers)   alphabet += 10;
                if (opts.symbols)   alphabet += 28;
                const len = pw.length;
                const entropy = (alphabet > 0 && len > 0) ? Math.log2(alphabet) * len : 0;

                const meter = document.getElementById('meter');
                const text = document.getElementById('meterText');
                let pct = Math.min(100, Math.round(entropy / 1.2));
                meter.style.width = pct + '%';
                meter.style.backgroundColor = pct < 35 ? '#ef4444' : (pct < 65 ? '#f59e0b' : '#10b981');
                text.textContent = `~${entropy.toFixed(0)} bits of entropy`;
            }
        })();
    </script>

</body>
</html>
