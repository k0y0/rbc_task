<?php

namespace App\Service\Import\Statistics;

use App\Utils\Import\ImportResults;
use App\Utils\Import\MedicineOrder\MedicineOrderData;

class StatisticsService
{
    const CONSONANTS = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'];

    public function getMedicineOrderStatistics(array $data): array
    {
        $statistics = [
            'topSale' => [
                'data' => []
            ],
            'countryByGroup' => [
                'data' => [],
                'maxCountry' => []
            ],
            'statusByFileType' => [
                'data' => [],
                'max' => []
            ],
            'consonantsSum' => 0,
        ];

        /** @var MedicineOrderData $medicineOrderData */
        foreach ($data as $medicineOrderData) {
            // top sale
            if (isset($statistics['topSale']['data'][$medicineOrderData->order])) {
                $statistics['topSale']['data'][$medicineOrderData->order]++;
            } else {
                $statistics['topSale']['data'][$medicineOrderData->order] = 1;
            }

            // countryByGroup
            if (isset($statistics['countryByGroup']['data'][$medicineOrderData->group])) {
                if (isset($statistics['countryByGroup']['data'][$medicineOrderData->group][$medicineOrderData->country])) {
                    $statistics['countryByGroup']['data'][$medicineOrderData->group][$medicineOrderData->country] ++;
                } else {
                    $statistics['countryByGroup']['data'][$medicineOrderData->group][$medicineOrderData->country] = 1;
                }
            } else {
                $statistics['countryByGroup']['data'][$medicineOrderData->group][$medicineOrderData->country] = 1;
            }

            // status by fileType
            if (isset($statistics['statusByFileType']['data'][$medicineOrderData->status])) {
                if (isset($statistics['statusByFileType']['data'][$medicineOrderData->status][$medicineOrderData->file])) {
                    $statistics['statusByFileType']['data'][$medicineOrderData->status][$medicineOrderData->file] ++;
                } else {
                    $statistics['statusByFileType']['data'][$medicineOrderData->status][$medicineOrderData->file] = 1;
                }
            } else {
                $statistics['statusByFileType']['data'][$medicineOrderData->status][$medicineOrderData->file] = 1;
            }

            // consonantsSum
            $customer = trim(strtolower($medicineOrderData->customer));
            $charsCount = count_chars($customer, 1);
            foreach ($charsCount as $charKey => $count) {
                if (in_array(chr($charKey), self::CONSONANTS)) {
                    $statistics['consonantsSum'] += $count;
                }
            }
        }
        // sort top sale
        arsort($statistics['topSale']['data']);

        // find the highest country by group

        $countryByGroupMax = 0;
        foreach ($statistics['countryByGroup']['data'] as $groupKey => $country ) {
            if (max($country) > $countryByGroupMax) {
                $countryByGroupMax = max($country);
            }
        }
        foreach ($statistics['countryByGroup']['data'] as $groupKey => $country ) {

            foreach ($country as $name => $count) {
                if ($count === $countryByGroupMax) {
                    $statistics['countryByGroup']['maxCountry'][] = $name;
                }
            }
        }

        // find max count by file type each status

        foreach ($statistics['statusByFileType']['data'] as $statusName => $files) {
            $statusMaxByFile = 0;

            foreach ($files as $file => $count) {
                if ($count > $statusMaxByFile) {
                    $statusMaxByFile = $count;
                }
            }

            foreach ($files as $file => $count) {
                if ($count === $statusMaxByFile) {
                    $statistics['statusByFileType']['max'][$statusName][] = $file;
                }
            }
        }

        return $statistics;
    }
}