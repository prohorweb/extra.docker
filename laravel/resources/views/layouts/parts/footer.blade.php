<footer class="bg-neutral-900 text-[var(--color-accent)]">

    <div class="py-5">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-wrap items-start">

                {{-- Левая: ссылки + соцсети --}}
                <div class="w-full md:w-1/2 flex flex-wrap">

                    <div class="w-full lg:w-1/2 text-center">
                        <ul class="list-none p-0 space-y-2">
                            <li><a href="http://piter.{{ config('app.domain') }}"  class="text-[var(--color-accent)] hover:opacity-75">ТРЦ «Питер»</a></li>
                            <li><a href="http://matros.{{ config('app.domain') }}" class="text-[var(--color-accent)] hover:opacity-75">«Матроса железняка»</a></li>
                            <li><a href="http://de-vision.ru"                      class="text-[var(--color-accent)] hover:opacity-75">De-Vision</a></li>
                        </ul>
                    </div>

                    <div class="w-full lg:w-1/2 flex items-center justify-center gap-3 mt-3">
                        <span>Мы в:</span>
                        {{-- VK --}}
                        <a href="http://vk.com/extrasport_ru" target="_blank"
                           class="text-[var(--color-accent)] hover:opacity-75 text-2xl leading-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15.684 0H8.316C1.592 0 0 1.592 0 8.316v7.368C0 22.408 1.592 24 8.316 24h7.368C22.408 24 24 22.408 24 15.684V8.316C24 1.592 22.408 0 15.684 0zm3.692 17.123h-1.744c-.66 0-.862-.525-2.049-1.714-1.033-1.01-1.49-.85-1.49.525v1.574c0 .393-.126.63-1.155.63-1.693 0-3.57-1.025-4.888-2.937C6.514 12.78 6 10.845 6 10.355c0-.248.098-.48.49-.48h1.744c.365 0 .504.168.645.56.71 2.05 1.898 3.845 2.388 3.845.183 0 .267-.084.267-.546V11.45c-.056-.98-.573-1.063-.573-1.413 0-.168.14-.336.365-.336h2.745c.308 0 .42.168.42.532v2.856c0 .308.14.42.225.42.183 0 .336-.112.672-.448 1.04-1.165 1.778-2.954 1.778-2.954.098-.196.267-.378.63-.378h1.744c.52 0 .632.266.52.63-.211.98-2.27 3.89-2.27 3.89-.168.28-.238.406 0 .728.168.238.728.728 1.1 1.165.683.77 1.205 1.42 1.345 1.867.14.434-.084.658-.49.658z"/>
                            </svg>
                        </a>
                        {{-- YouTube --}}
                        <a href="http://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured" target="_blank"
                           class="text-[var(--color-accent)] hover:opacity-75 text-2xl leading-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    </div>

                </div>

                {{-- Правая: приложения --}}
                <div class="w-full md:w-1/2 flex justify-end">
                    <div class="flex flex-col">

                        <a href="http://play.google.com/store/apps/details?id=air.com.extrasport"
                           target="_blank"
                           onclick="ym(21525628,'reachGoal','download_app')"
                           class="flex items-center justify-end my-3 pl-3 gap-3 text-[var(--color-accent)] hover:opacity-75">
                            <p class="m-0 text-right leading-tight">Доступно в<br><b>GOOGLE PLAY</b></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.75em" height="1.75em" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.018 13.298l-3.919 2.218-3.515-3.493 3.543-3.521 3.891 2.202a1.49 1.49 0 0 1 0 2.594zM1.337.924a1.49 1.49 0 0 0-.227.819v20.512c0 .33.094.638.261.897l.01.015 11.494-11.42v-.024L1.337.924zm12.207 12.084l3.415-3.392L3.573.5a1.49 1.49 0 0 0-.792-.5l10.763 12.908zm-1.601 1.591L.49 22.59a1.49 1.49 0 0 0 .82-.207l13.129-7.437-2.496-2.469z"/>
                            </svg>
                        </a>

                        <a href="http://itunes.apple.com/ru/app/extra-sport/id1462883244?mt=8"
                           target="_blank"
                           onclick="ym(21525628,'reachGoal','download_app')"
                           class="flex items-center justify-end my-3 pl-3 gap-3 text-[var(--color-accent)] hover:opacity-75">
                            <p class="m-0 text-right leading-tight">Загрузите в<br><b>APP STORE</b></p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.75em" height="1.75em" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.152 6.896c-.948 0-2.415-1.078-3.96-1.04-2.04.027-3.91 1.183-4.961 3.014-2.117 3.675-.54 9.103 1.519 12.09 1.013 1.454 2.208 3.09 3.792 3.039 1.52-.065 2.09-.987 3.935-.987 1.831 0 2.35.987 3.96.948 1.637-.026 2.676-1.48 3.676-2.948 1.156-1.688 1.636-3.325 1.662-3.415-.039-.013-3.182-1.221-3.22-4.857-.026-3.04 2.48-4.494 2.597-4.559-1.429-2.09-3.623-2.324-4.39-2.376-2-.156-3.675 1.09-4.61 1.09zM15.53 3.83c.843-1.012 1.4-2.427 1.245-3.83-1.207.052-2.662.805-3.532 1.818-.78.896-1.454 2.338-1.273 3.714 1.338.104 2.715-.688 3.56-1.701z"/>
                            </svg>
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Нижняя полоса --}}
    <div class="bg-black text-[0.85rem] py-3">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-wrap items-center justify-between">
                <span>&copy; {{ date('Y') }} EХTRASPORT, LLC</span>
                <a href="http://piter.{{ config('app.domain') }}/legal/"
                   target="_blank"
                   class="text-[var(--color-accent)] no-underline hover:opacity-75">
                    Правовая информация
                </a>
            </div>
        </div>
    </div>

</footer>