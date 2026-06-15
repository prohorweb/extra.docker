<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $host = $request->getHost();

        $itemClubs = [
            [
                'name'    => 'ТРЦ «Питер»',
                'address' => 'Санкт-Петербург, ул. Типанова, 21',
                'image'   => asset('img/clubs/welcom-block-img-2.jpg'),
                'url'     => 'http://piter.' . $host,
            ],
            [
                'name'    => '«Матроса железняка»',
                'address' => 'Санкт-Петербург, ул. Матроса Железняка, 57А',
                'image'   => asset('img/clubs/welcom-block-img-4.jpg'),
                'url'     => 'http://matros.' . $host,
            ],
            [
                'name'    => 'De-Vision',
                'address' => 'Санкт-Петербург, пр. Культуры, 1',
                'image'   => asset('img/clubs/welcom-block-img-5.jpg'),
                'url'     => 'http://de-vision.new',
            ],
        ];

        $placemarks = [
            [
                'coordinates' => '59.8499, 30.2953',
                'hint'        => 'EXTRASPORT Питер',
                'icon'        => asset('img/marker.png'),
                'url'         => 'http://piter.' . $host,
            ],
            [
                'coordinates' => '59.9880, 30.3605',
                'hint'        => 'EXTRASPORT Матроса Железняка',
                'icon'        => asset('img/marker.png'),
                'url'         => 'http://matros.' . $host,
            ],
            [
                'coordinates' => '60.0335, 30.3683',
                'hint'        => 'De-vision',
                'icon'        => asset('img/marker2.png'),
                'url'         => 'http://de-vision.new',
            ],
        ];


        if (in_array($host, ['extra.new', 'www.extra.new'])) {
            return view('pages.welcome', [
                'hero' => [
                    'video' => asset('video/bg_moution.mp4'),
                    'logo'  => asset('img/logo.svg'),
                    'heading' => 'Сеть фитнес клубов на результат!',
                    'cta' => [
                        'text' => 'Выберите клуб',
                        'url' => '#clubs',
                        'url-mobile' => '#clubs-mobile',
                    ],
                ],
                'clubs' => $itemClubs,
                'placemarks' => $placemarks,
                'seo' => null,
                'showHeader' => false,
            ]);
        }

        if (in_array($host, ['de-vision.new', 'www.de-vision.new']) || str_contains($host, '.extra.new')) {
            $subdomain = explode('.', $host)[0] ?? 'club';

            $clubData = [
                'piter' => [
                    'title'               => 'EXTRASPORT ТК «ПИТЕР»',
                    'tel'                 => '+7 (812) 966-92-23',
                    'email'               => 'piter@extrasport.ru',
                    'address'             => 'Санкт-Петербург, ул. Типанова, 21',
                    'start_work'          => '07:00–23:00',
                    'start_work_weekend'  => '09:00–22:00',
                    'coordinates'         => '59.8499, 30.2953',
                ],
                'matros' => [
                    'title'               => 'EXTRASPORT ул. Матроса Железняка',
                    'tel'                 => '+7 (812) 981-77-50',
                    'email'               => 'matros@extrasport.ru',
                    'address'             => 'Санкт-Петербург, ул. Матроса Железняка, 57А',
                    'start_work'          => '07:00–23:00',
                    'start_work_weekend'  => '09:00–22:00',
                    'coordinates'         => '59.9880, 30.3605',
                ],
            ];

            $club = $clubData[$subdomain] ?? [
                'title'               => 'EXTRASPORT ' . ucfirst($subdomain),
                'tel'                 => '',
                'email'               => '',
                'address'             => '',
                'start_work'          => '',
                'start_work_weekend'  => '',
                'coordinates'         => '59.95, 30.32',
            ];

            // TODO: replace with DB model queries when ready
            $banners = [
                [
                    'title'  => 'Годовой безлимитный фитнес',
                    'title2' => 'Специальное предложение',
                    'intro'  => '+ массаж и 2 абонемент в подарок!',
                    'url'    => '/card/type/',
                    'img'    => asset('img/slide-2.jpeg'),
                ],
                [
                    'title'  => 'Годовой безлимитный бассейн',
                    'title2' => 'Специальное предложение',
                    'intro'  => '+ 2 года в подарок!',
                    'url'    => '/card/type/',
                    'img'    => asset('img/slide-3.jpeg'),
                ],
            ];

            // TODO: replace with DB model queries when ready
            $shares = [
                [
                    'title'  => 'Годовой безлимитный фитнес 9 900 ₽',
                    'title2' => 'Акция',
                    'intro'  => '+ массаж и 2-й абонемент в подарок',
                    'img'    => '1-1755502772.jpg',
                    'alias'  => 'godovoy-fitnes',
                ],
                [
                    'title'  => 'Абонемент на 3 месяца',
                    'title2' => 'Специальное предложение',
                    'intro'  => 'Групповые тренировки + тренажёрный зал',
                    'img'    => '1710512908930-1710512949.jpg',
                    'alias'  => '3-months',
                ],
                [
                    'title'  => 'Безлимитный бассейн',
                    'title2' => 'Новинка',
                    'intro'  => 'Водные занятия для взрослых и детей',
                    'img'    => '00e986a8-331b-4d14-9b2e-6b165b76bd7a-1695757896.jpg',
                    'alias'  => 'pool',
                ],
            ];

            return view('pages.home', [
                'club'    => $club,
                'banners' => $banners,
                'shares'  => $shares,
                'metros'  => [],
                'seo'     => null,
            ]);
        }

        return view('pages.welcome', [
            'clubs' => $itemClubs,
            'placemarks' => $placemarks,
            'seo' => null,
            'showHeader' => false,
        ]);
    }
}

