<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function index()
    {
        // initial page load
        return view('password', [
            'result' => null,
            'options' => [
                'length' => 16,
                'uppercase' => true,
                'lowercase' => true,
                'numbers' => true,
                'symbols' => true,
                'avoid_ambiguous' => true,
            ],
        ]);
    }

    public function generate(Request $request)
    {
        $data = $this->validateOptions($request);
        $password = $this->makePassword($data);

        return view('password', [
            'result' => $password,
            'options' => $data,
        ]);
    }

    public function apiGenerate(Request $request)
    {
        $data = $this->validateOptions($request);
        $password = $this->makePassword($data);

        return response()->json([
            'password' => $password,
            'length' => $data['length'],
            'options' => $data,
        ]);
    }

    private function validateOptions(Request $request): array
    {
        $data = $request->validate([
            'length' => ['required','integer','min:8','max:128'],
            'uppercase' => ['nullable','boolean'],
            'lowercase' => ['nullable','boolean'],
            'numbers'   => ['nullable','boolean'],
            'symbols'   => ['nullable','boolean'],
            'avoid_ambiguous' => ['nullable','boolean'],
        ]);

        // normalize checkboxes
        foreach (['uppercase','lowercase','numbers','symbols','avoid_ambiguous'] as $key) {
            $data[$key] = (bool) ($data[$key] ?? false);
        }

        // must have at least one character class
        if (!($data['uppercase'] || $data['lowercase'] || $data['numbers'] || $data['symbols'])) {
            // force lowercase if user unchecked all
            $data['lowercase'] = true;
        }

        return $data;
    }

    private function makePassword(array $opt): string
    {
        // character sets
        $upper   = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // removed I, O (ambiguous)
        $lower   = 'abcdefghjkmnpqrstuvwxyz'; // removed i, l, o
        $digits  = '23456789';                // removed 0,1
        $symbols = '!@#$%^&*()-_=+[]{};:,.?';

        // if ambiguity allowed, add full sets back
        if (!$opt['avoid_ambiguous']) {
            $upper  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $lower  = 'abcdefghijklmnopqrstuvwxyz';
            $digits = '0123456789';
        }

        $pools = [];
        if ($opt['uppercase']) $pools['upper'] = $upper;
        if ($opt['lowercase']) $pools['lower'] = $lower;
        if ($opt['numbers'])   $pools['digits'] = $digits;
        if ($opt['symbols'])   $pools['symbols'] = $symbols;

        // guarantee at least one from each selected type
        $chars = [];
        foreach ($pools as $pool) {
            $chars[] = $pool[random_int(0, strlen($pool) - 1)];
        }

        // combined pool
        $all = implode('', $pools);

        // fill the rest
        while (count($chars) < $opt['length']) {
            $chars[] = $all[random_int(0, strlen($all) - 1)];
        }

        // cryptographically shuffle (Fisher–Yates using random_int)
        for ($i = count($chars) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$chars[$i], $chars[$j]] = [$chars[$j], $chars[$i]];
        }

        return implode('', $chars);
    }
}
