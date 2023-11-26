<?php

namespace App\Controller\Dashboard;

use App\Form\Dashboard\ImportFilesFormType;
use App\Service\Import\DataImportService;
use App\Service\Import\Statistics\StatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(
        Request $request,
        DataImportService $dataImportService,
        StatisticsService $statisticsService,
    ): Response {

        $importFiles = $dataImportService->getImportFiles();
        $form = $this->createForm(ImportFilesFormType::class, [], [
            'importFiles' => $importFiles,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filesSelectedKeys = $form->get('files')->getData();
            $filesSelected = [];
            foreach ($filesSelectedKeys as $importFileKey) {
                $filesSelected[] = $importFiles[$importFileKey];
            }

            $importData = $dataImportService->importData($filesSelected);
            $statistics = $statisticsService->getMedicineOrderStatistics($importData);
        }

        return $this->render('dashboard/index.html.twig', [
            'form' => $form->createView(),
            'statistics' => $statistics ?? null,
        ]);
    }
}
