<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetTours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tours:get {service?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get tours from the API';

    protected $services = [
        'heavenly_tours' => \App\Service\Tours\HeavenlyTours::class,
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->argument('service')) {
            if (!array_key_exists($this->argument('service'), $this->services)) {
                $this->error('Service not found');
                return 1;
            }
            if (!class_exists($this->services[$this->argument('service')])) {
                $this->error('Service class not found');
                return 1;
            }
            $this->services = [$this->services[$this->argument('service')]];
        }
        $startTime = now()->format('Y-m-d');
        $endTime = now()->addWeeks(2)->format('Y-m-d');
        $dateTimes = [];
        while ($startTime < $endTime) {
            $dateTimes[] = $startTime;
            $startTime = \Carbon\Carbon::parse($startTime)->addDay()->format('Y-m-d');
        }
        foreach ($this->services as $service) {
            DB::beginTransaction();
            $service = new $service();
            $data = $service->getTours();
            foreach ($data as $tour) {
                try {
                    if (Product::query()->where('slug', $tour['id'])->exists()) {
                        $this->info('Tour ' . $tour['name'] . ' already exists');
                        $detail = Product::query()->where('slug', $tour['id'])->first();
                    }else{
                        $info = $service->getTour($tour['id']);
                        $detail = Product::query()->firstOrCreate(['slug' => $info['id']], [
                            'name' => $info['name'],
                            'description' => $info['description'],
                            'thumbnail' => $info['thumbnail'],
                        ]);
                        $this->info('Tour ' . $detail->name . ' has been saved');
                    }
                    foreach ($dateTimes as $dateTime) {
                        $args = [
                            'id' => $detail->id,
                            'travel_date' => $dateTime,
                        ];
                        if ($service->availableTour($args)) {
                            $price = $service->searchTours($args);
                            foreach ($price as $p) {
                                if ($p['id'] === $detail->id) {
                                    $detail->availabilities()->create([
                                        'price' => $price['price'],
                                        'start_time' => now()->format('Y-m-d H:i:s'),
                                        'end_time' => now()->addDays(7)->format('Y-m-d H:i:s'),
                                    ]);
                                }
                            }
                        } else {
                            $this->info('Tour ' . $detail->name . ' is not available');
                        }
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->error($e->getMessage());
                }
            }
        }
    }
}
