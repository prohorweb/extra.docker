<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $host = $request->getHost();

        $demoClubs = [
            [
                'name' => 'ТРЦ «Питер»',
                'address' => 'Санкт-Петербург, ул. Типанова, 21',
                'image' => '/img/clubs/welcom-block-img-2.jpg',
                'url' => 'http://piter.' . $host,
            ],
            [
                'name' => '«Матроса железняка»',
                'address' => 'Санкт-Петербург, ул. Матроса Железняка, 57А',
                'image' => '/img/clubs/welcom-block-img-4.jpg',
                'url' => 'http://matros.' . $host,
            ],
            [
                'name' => 'De-Vision',
                'address' => 'Санкт-Петербург, пр. Культуры, 1',
                'image' => '/img/clubs/welcom-block-img-5.jpg',
                'url' => 'http://de-vision.new',
            ],
        ];

        $demoPlacemarks = [
            [
                'lat' => 59.8499,
                'lng' => 30.2953,
                'hint' => 'EХTRASPORT Питер',
                'icon' => '/images/marker.png',
                'url' => 'http://piter.' . $host,
            ],
            [
                'lat' => 59.9880,
                'lng' => 30.3605,
                'hint' => 'EХTRASPORT Матроса Железняка',
                'icon' => '/images/marker.png',
                'url' => 'http://matros.' . $host,
            ],
            [
                'lat' => 60.0335,
                'lng' => 30.3683,
                'hint' => 'De-vision',
                'icon' => '/images/marker2.png',
                'url' => 'http://de-vision.ru',
            ],
        ];

        if (in_array($host, ['extra.new', 'www.extra.new'])) {
            return view('pages.welcome', [
                'hero' => [
                    'video' => '/video/bg_moution.mp4',
                    'logo' => asset('img/logo.svg'),
                    'heading' => 'Сеть фитнес клубов на результат!',
                    'cta' => [
                        'text' => 'Выберите клуб',
                        'url' => '#clubs',
                        'url-mobile' => '#clubs-mobile',
                    ],
                ],
                'clubs' => $demoClubs,
                'placemarks' => $demoPlacemarks,
                'seo' => null,
            ]);
        }

        if (in_array($host, ['de-vision.new', 'www.de-vision.new']) || str_contains($host, '.extra.new')) {
            $clubName = ucfirst(explode('.', $host)[0] ?? 'Club');
            return view('pages.home', [
                'club' => ['name' => $clubName],
                'hero' => [
                    'heading' => $clubName,
                ],
                'services' => [
                    ['title' => 'Тренажерный зал', 'description' => 'Современное оборудование для любых целей'],
                    ['title' => 'Групповые программы', 'description' => 'Йога, пилатес, зумба и более 30 направлений'],
                    ['title' => 'Персональные тренировки', 'description' => 'Индивидуальные программы с тренером'],
                ],
                'theme' => [],
                'seo' => null,
            ]);
        }

        return view('pages.welcome', [
            'clubs' => $demoClubs,
            'placemarks' => $demoPlacemarks,
            'seo' => null,
        ]);
    }
}

