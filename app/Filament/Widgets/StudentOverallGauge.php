<?php

namespace App\Filament\Resources\Students\Widgets;

use App\Models\Students;
use Filament\Support\RawJs;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class StudentOverallGauge extends ApexChartWidget
{
      public ?Students $record = null;

      protected static ?string $chartId = 'studentOverallGauge';

      protected static ?string $heading = 'Aproveitamento';

      protected static ?int $contentHeight = 240;

      protected ?string $pollingInterval = null;

      protected function getOptions(): array
      {
            $pct = $this->resolvePercentage();

            $color = match (true) {
                  $pct >= 78 => '#22c55e', // success
                  $pct >= 60 => '#f59e0b', // warning
                  default    => '#ef4444', // danger
            };

            return [
                  'chart' => [
                        'type' => 'radialBar',
                        'height' => 240,
                        'fontFamily' => 'inherit',
                        'toolbar' => ['show' => false],
                        'sparkline' => ['enabled' => false],
                  ],
                  'series' => [(float) $pct],
                  'labels' => ['Geral'],
                  'colors' => [$color],
                  'plotOptions' => [
                        'radialBar' => [
                              'startAngle' => -135,
                              'endAngle' => 135,
                              'hollow' => [
                                    'size' => '62%',
                              ],
                              'track' => [
                                    'background' => '#E5E9F0',
                                    'strokeWidth' => '100%',
                              ],
                              'dataLabels' => [
                                    'show' => true,
                                    'name' => [
                                          'show' => true,
                                          'fontSize' => '13px',
                                          'offsetY' => 24,
                                    ],
                                    'value' => [
                                          'show' => true,
                                          'fontSize' => '28px',
                                          'fontWeight' => 700,
                                          'offsetY' => -12,
                                    ],
                              ],
                        ],
                  ],
                  'stroke' => [
                        'lineCap' => 'round',
                  ],
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

      private function resolvePercentage(): float
      {
            $student = $this->record;

            if (! $student) {
                  return 0.0;
            }

            $latest = $student->results()
                  ->with('assessment')
                  ->get()
                  ->sortByDesc(fn($r) => $r->assessment?->assessment_date)
                  ->first();

            if (! $latest || (int) $latest->total_questions === 0) {
                  return 0.0;
            }

            return round(
                  ((float) $latest->correct_answers / (float) $latest->total_questions) * 100,
                  1
            );
      }
}
