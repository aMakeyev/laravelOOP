<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new ShatersClientNextContactEmail());
Artisan::add(new ShatersCalculationDeliveryDateNoticeEmail());
Artisan::add(new ClientNextContactEmail());
Artisan::add(new ClientSubscribeEmail());
Artisan::add(new CalculationPrePaidNeedMeasureEmail());
Artisan::add(new CalculationPrePaidNeedRecommendListEmail());
Artisan::add(new CalculationNeedControlInstallEmail());
Artisan::add(new CalculationOrderEndNoticeEmail());
Artisan::add(new CalculationDeliveryDateNoticeEmail());
