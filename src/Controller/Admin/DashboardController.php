<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Entity\Parser;
use App\Entity\ParserLog;
use App\Repository\NewsRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $newsCount      = $this->get('doctrine')->getRepository(News::class)->count([]);
        $parserLogCount = $this->get('doctrine')->getRepository(ParserLog::class)->count([]);

        return $this->render('welcome.html.twig', [
            'news_count'       => $newsCount,
            'parser_log_count' => $parserLogCount,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('News Parser Back');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Parsers', null, Parser::class),
            MenuItem::linkToCrud('News', null, News::class),
            MenuItem::linkToCrud('Parsers logs', null, ParserLog::class),
        ];
    }
}
