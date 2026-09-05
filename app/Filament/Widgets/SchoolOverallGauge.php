<?php

namespace App\Filament\Widgets;

use App\Models\AssessmentResult;
use Filament\Facades\Filament;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class SchoolOverallGauge extends ApexChartWidget
{
      protected static ?string $chartId = 'schoolOverallGauge';

      protected static ?string $heading = 'Média geral';

      protected static ?int $contentHeight = 220;

      protected ?string $pollingInterval = null;

      protected function getOptions(): array
      {
            $pct = $this->resolveAverage();

            $color = match (true) {
                  $pct >= 70 => '#22c55e',
                  $pct >= 50 => '#f59e0b',
                  default => '#ef4444',
            };

            return [
                  'chart' => [
                        'type' => 'radialBar',
                        'height' => 220,
                        'fontFamily' => 'inherit',
                        'toolbar' => ['show' => false],
                  ],
                  'series' => [(float) $pct],
                  'labels' => ['Escola'],
                  'colors' => [$color],
                  'plotOptions' => [
                        'radialBar' => [
                              'startAngle' => -135,
                              'endAngle' => 135,
                              'hollow' => ['size' => '62%'],
                              'track' => [
                                    'background' => '#E5E9F0',
                                    'strokeWidth' => '100%',
                              ],
                              'dataLabels' => [
                                    'show' => true,
                                    'name' => [
                                          'show' => true,
                                          'fontSize' => '12px',
                                          'offsetY' => 22,
                                    ],
                                    'value' => [
                                          'show' => true,
                                          'fontSize' => '26px',
                                          'fontWeight' => 700,
                                          'offsetY' => -10,
                                    ],
                              ],
                        ],
                  ],
                  'stroke' => ['lineCap' => 'round'],
            ];
      }

      protected function extraJsOptions(): ?RawJs
      {
            return RawJs::make(<<<'JS'
        {
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        value: {
                            formatter: function (val) {
                                return val.toFixed(1).replace('.', ',') + '%'
                            }
                        }
                    }
                }
            }
        }
        JS);
      }

      private function resolveAverage(): float
      {
            $schoolId = Filament::getTenant()?->id;

            if (! $schoolId) {
                  return 0.0;
            }

            $avg = AssessmentResult::query()
                  ->whereHas('assessment', fn($q) => $q->where('school_id', $schoolId))
                  ->select(DB::raw('
                ROUND(
                    AVG(
                        CASE
                            WHEN total_questions > 0
                            THEN (correct_answers * 100.0 / total_questions)
                            ELSE NULL
                        END
                    ), 1
                ) as average
            '))
                  ->value('average');

            return (float) ($avg ?? 0);
      }
}
