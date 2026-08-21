<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body
    class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900 !rounded-2xl">
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 p-2 ml-2 !rounded-2xl">
        <div
            class="bg-muted p-8 border border-zinc-800 relative hidden h-full flex-col text-white lg:flex dark:border-e dark:border-neutral-800 !rounded-2xl">
            <div class="absolute inset-0 bg-neutral-900 !rounded-2xl p-4 overflow-hidden">
                <canvas class="cyber-grid-canvas absolute inset-0 h-full w-full rounded-2xl pointer-events-none"
                    style="border-radius: 2px" data-pixel-size="9" data-speed="2" data-color-base="#05050a"
                    data-color-a="#8b5cf6" data-color-b="#d946ef" data-color-c="#ff1744" data-color-d="#e11d48"
                    data-color-base-light="#05050a" data-color-a-light="#8b5cf6" data-color-b-light="#d946ef"
                    data-color-c-light="#ff1744" data-color-d-light="#e11d48" aria-hidden="true">
                </canvas>
            </div>

            @once
                <script>
                    (function () {
                        'use strict';

                        var VERT_SRC = [
                            'attribute vec2 aPos;',
                            'void main() {',
                            '  gl_Position = vec4(aPos, 0.0, 1.0);',
                            '}'
                        ].join('\n');

                        var FRAG_SRC = [
                            'precision highp float;',
                            'uniform vec2 uResolution;',
                            'uniform float uTime;',
                            'uniform float uPixelSize;',
                            'uniform vec3 uColorBase;',
                            'uniform vec3 uColorA;',
                            'uniform vec3 uColorB;',
                            'uniform vec3 uColorC;',
                            'uniform vec3 uColorD;',
                            '',
                            'float hash(vec2 p) {',
                            '  p = fract(p * vec2(123.34, 456.21));',
                            '  p += dot(p, p + 45.32);',
                            '  return fract(p.x * p.y);',
                            '}',
                            '',
                            'float noise(vec2 p) {',
                            '  vec2 i = floor(p);',
                            '  vec2 f = fract(p);',
                            '  float a = hash(i);',
                            '  float b = hash(i + vec2(1.0, 0.0));',
                            '  float c = hash(i + vec2(0.0, 1.0));',
                            '  float d = hash(i + vec2(1.0, 1.0));',
                            '  vec2 u = f * f * (3.0 - 2.0 * f);',
                            '  return mix(a, b, u.x) + (c - a) * u.y * (1.0 - u.x) + (d - b) * u.x * u.y;',
                            '}',
                            '',
                            'float fbm(vec2 p) {',
                            '  float v = 0.0;',
                            '  float amp = 0.5;',
                            '  for (int i = 0; i < 3; i++) {',
                            '    v += amp * noise(p);',
                            '    p *= 2.03;',
                            '    amp *= 0.55;',
                            '  }',
                            '  return v;',
                            '}',
                            '',
                            'void main() {',
                            '  vec2 fragCoord = gl_FragCoord.xy;',
                            '  vec2 gridUV = fragCoord / uPixelSize;',
                            '  vec2 cell = floor(gridUV);',
                            '  vec2 cellUV = fract(gridUV);',
                            '',
                            '  vec2 flow = cell * 0.06;',
                            '  vec2 drift = vec2(uTime * 0.035, -uTime * 0.025);',
                            '  vec2 warp = vec2(fbm(flow + drift), fbm(flow + drift + 19.3));',
                            '  float n = fbm(flow + drift + warp * 1.4);',
                            '  float hueN = fbm(flow * 0.5 - drift * 0.6 + 7.0);',
                            '',
                            '  vec3 accent = mix(uColorA, uColorB, smoothstep(0.0, 0.33, hueN));',
                            '  accent = mix(accent, uColorC, smoothstep(0.33, 0.66, hueN));',
                            '  accent = mix(accent, uColorD, smoothstep(0.66, 1.0, hueN));',
                            '',
                            '  float glow = smoothstep(0.32, 0.92, n);',
                            '  vec3 base = mix(uColorBase, accent, glow);',
                            '',
                            '  float gap = 0.08;',
                            '  float inCellX = smoothstep(0.0, gap, cellUV.x) * smoothstep(1.0, 1.0 - gap, cellUV.x);',
                            '  float inCellY = smoothstep(0.0, gap, cellUV.y) * smoothstep(1.0, 1.0 - gap, cellUV.y);',
                            '  float inCell = inCellX * inCellY;',
                            '',
                            '  float bevel = (cellUV.x - cellUV.y) * 0.10;',
                            '  vec3 cube = base * (1.0 + bevel);',
                            '',
                            '  vec3 color = mix(uColorBase * 0.55, cube, inCell);',
                            '  color += accent * pow(glow, 3.0) * 0.30;',
                            '',
                            '  vec2 center = uResolution * 0.5;',
                            '  float d = distance(fragCoord, center) / length(center);',
                            '  color *= 1.0 - d * 0.22;',
                            '',
                            '  gl_FragColor = vec4(color, 1.0);',
                            '}'
                        ].join('\n');

                        function hexToVec3(hex) {
                            var v = parseInt(String(hex).replace('#', ''), 16);
                            return [((v >> 16) & 255) / 255, ((v >> 8) & 255) / 255, (v & 255) / 255];
                        }

                        function compileShader(gl, type, src) {
                            var shader = gl.createShader(type);
                            gl.shaderSource(shader, src);
                            gl.compileShader(shader);
                            if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
                                console.error('cyber-grid shader error:', gl.getShaderInfoLog(shader));
                                gl.deleteShader(shader);
                                return null;
                            }
                            return shader;
                        }

                        function initCyberGrid(canvas) {
                            var opts = {
                                pixelSize: parseFloat(canvas.dataset.pixelSize) || 9,
                                speed: parseFloat(canvas.dataset.speed) || 1,
                                colorBase: canvas.dataset.colorBase || '#0a0a0f',
                                colorA: canvas.dataset.colorA || '#00e5ff',
                                colorB: canvas.dataset.colorB || '#3b82f6',
                                colorC: canvas.dataset.colorC || '#a855f7',
                                colorD: canvas.dataset.colorD || '#10e0a0'
                            };

                            var optsLight = {
                                colorBase: canvas.dataset.colorBaseLight || '#eef1f5',
                                colorA: canvas.dataset.colorALight || '#0891b2',
                                colorB: canvas.dataset.colorBLight || '#2563eb',
                                colorC: canvas.dataset.colorCLight || '#7c3aed',
                                colorD: canvas.dataset.colorDLight || '#0d9488'
                            };

                            var gl = canvas.getContext('webgl', { antialias: false, alpha: false, powerPreference: 'high-performance' }) ||
                                canvas.getContext('experimental-webgl');

                            if (!gl) {
                                canvas.style.display = 'none';
                                if (canvas.parentElement) {
                                    canvas.parentElement.style.background =
                                        'radial-gradient(circle at 30% 20%, #10202a 0%, #0a0a0f 70%)';
                                }
                                return;
                            }

                            var vShader = compileShader(gl, gl.VERTEX_SHADER, VERT_SRC);
                            var fShader = compileShader(gl, gl.FRAGMENT_SHADER, FRAG_SRC);
                            if (!vShader || !fShader) return;

                            var program = gl.createProgram();
                            gl.attachShader(program, vShader);
                            gl.attachShader(program, fShader);
                            gl.linkProgram(program);
                            if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
                                console.error('cyber-grid program error:', gl.getProgramInfoLog(program));
                                return;
                            }
                            gl.useProgram(program);

                            var quad = new Float32Array([-1, -1, 1, -1, -1, 1, 1, -1, 1, 1, -1, 1]);
                            var buffer = gl.createBuffer();
                            gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
                            gl.bufferData(gl.ARRAY_BUFFER, quad, gl.STATIC_DRAW);

                            var aPos = gl.getAttribLocation(program, 'aPos');
                            gl.enableVertexAttribArray(aPos);
                            gl.vertexAttribPointer(aPos, 2, gl.FLOAT, false, 0, 0);

                            var uResolution = gl.getUniformLocation(program, 'uResolution');
                            var uTime = gl.getUniformLocation(program, 'uTime');
                            var uPixelSize = gl.getUniformLocation(program, 'uPixelSize');
                            var uColorBase = gl.getUniformLocation(program, 'uColorBase');
                            var uColorA = gl.getUniformLocation(program, 'uColorA');
                            var uColorB = gl.getUniformLocation(program, 'uColorB');
                            var uColorC = gl.getUniformLocation(program, 'uColorC');
                            var uColorD = gl.getUniformLocation(program, 'uColorD');

                            function isDarkMode() {
                                return document.documentElement.classList.contains('dark');
                            }

                            function applyPalette() {
                                var p = isDarkMode() ? opts : optsLight;
                                gl.uniform3fv(uColorBase, hexToVec3(p.colorBase));
                                gl.uniform3fv(uColorA, hexToVec3(p.colorA));
                                gl.uniform3fv(uColorB, hexToVec3(p.colorB));
                                gl.uniform3fv(uColorC, hexToVec3(p.colorC));
                                gl.uniform3fv(uColorD, hexToVec3(p.colorD));
                            }

                            applyPalette();

                            var dpr = Math.min(window.devicePixelRatio || 1, 1.5);
                            var width = 0;
                            var height = 0;

                            function resize() {
                                var rect = canvas.getBoundingClientRect();
                                var w = Math.max(1, Math.round(rect.width * dpr));
                                var h = Math.max(1, Math.round(rect.height * dpr));
                                if (w === width && h === height) return;
                                width = w;
                                height = h;
                                canvas.width = width;
                                canvas.height = height;
                                gl.viewport(0, 0, width, height);
                                gl.uniform2f(uResolution, width, height);
                                gl.uniform1f(uPixelSize, opts.pixelSize * dpr);
                            }

                            if ('ResizeObserver' in window) {
                                new ResizeObserver(resize).observe(canvas);
                            } else {
                                window.addEventListener('resize', resize);
                            }
                            resize();

                            var reduceMotion = !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
                            var speed = reduceMotion ? opts.speed * 0.15 : opts.speed;

                            var rafId = null;
                            var lastTime = 0;
                            var elapsed = 0;

                            function frame(now) {
                                var dt = (now - lastTime) / 1000;
                                lastTime = now;
                                elapsed += dt * speed;
                                gl.uniform1f(uTime, elapsed);
                                gl.drawArrays(gl.TRIANGLES, 0, 6);
                                rafId = requestAnimationFrame(frame);
                            }

                            function play() {
                                if (rafId) return;
                                lastTime = performance.now();
                                rafId = requestAnimationFrame(frame);
                            }

                            function stop() {
                                if (rafId) {
                                    cancelAnimationFrame(rafId);
                                    rafId = null;
                                }
                            }

                            document.addEventListener('visibilitychange', function () {
                                if (document.hidden) { stop(); } else { play(); }
                            });

                            if ('IntersectionObserver' in window) {
                                var io = new IntersectionObserver(function (entries) {
                                    entries.forEach(function (entry) {
                                        if (entry.isIntersecting) { play(); } else { stop(); }
                                    });
                                }, { threshold: 0.01 });
                                io.observe(canvas);
                            }

                            var themeObserver = new MutationObserver(function () {
                                applyPalette();
                            });
                            themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

                            play();
                        }

                        function boot() {
                            var canvases = document.querySelectorAll('.cyber-grid-canvas:not([data-cyber-grid-init])');
                            canvases.forEach(function (c) {
                                c.setAttribute('data-cyber-grid-init', '1');
                                initCyberGrid(c);
                            });
                        }

                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', boot);
                        } else {
                            boot();
                        }
                    })();
                </script>
            @endonce
        </div>
        <div class="w-full lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden"
                    wire:navigate>
                    <span class="flex h-9 w-9 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                    </span>

                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                {{ $slot }}
            </div>
        </div>
    </div>

    @persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>