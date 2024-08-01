<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Profit & Loss')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Profit & Loss')); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('script-page'); ?>
<script>
    $(document).ready(function() {
        $("#filter").click(function() {
            $("#show_filter").toggle();
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('action-btn'); ?>


    <div class="float-end">
        <?php echo e(Form::open(['route' => ['profit.loss.print']])); ?>

        <input type="hidden" name="start_date" class="start_date">
        <input type="hidden" name="end_date" class="end_date">
        <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Print')); ?>"
            data-original-title="<?php echo e(__('Print')); ?>"><i class="ti ti-printer"></i></button>
        <?php echo e(Form::close()); ?>

    </div>

    <div class="float-end me-2">
        <?php echo e(Form::open(['route' => ['profit.loss.export']])); ?>

        <input type="hidden" name="start_date" class="start_date">
        <input type="hidden" name="end_date" class="end_date">
        <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Export')); ?>"
            data-original-title="<?php echo e(__('Export')); ?>"><i class="ti ti-file-export"></i></button>
        <?php echo e(Form::close()); ?>

    </div>


    <div class="float-end me-2" id="filter">
        <button id="filter" class="btn btn-sm btn-primary"><i class="ti ti-filter"></i></button>
    </div>

    <div class="float-end me-2">
        <a href="<?php echo e(route('report.profit.loss' , 'horizontal')); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Horizontal View')); ?>"
            data-original-title="<?php echo e(__('Horizontal View')); ?>"><i class="ti ti-separator-vertical"></i></a>
    </div>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card" id="show_filter" style="display:none;">
                    <div class="card-body">
                        <?php echo e(Form::open(['route' => ['report.profit.loss'], 'method' => 'GET', 'id' => 'report_trial_balance'])); ?>

                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::date('start_date', $filter['startDateRange'], ['class' => 'startDate form-control'])); ?>

                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::date('end_date', $filter['endDateRange'], ['class' => 'endDate form-control'])); ?>

                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-auto mt-4">
                                <div class="row">
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('report_trial_balance').submit(); return false;"
                                            data-bs-toggle="tooltip" title="<?php echo e(__('Apply')); ?>"
                                            data-original-title="<?php echo e(__('apply')); ?>">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>

                                        <a href="<?php echo e(route('trial.balance')); ?>" class="btn btn-sm btn-danger "
                                            data-bs-toggle="tooltip" title="<?php echo e(__('Reset')); ?>"
                                            data-original-title="<?php echo e(__('Reset')); ?>">
                                            <span class="btn-inner--icon"><i
                                                    class="ti ti-trash-off text-white-off "></i></span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
    </div>

    <?php
        $authUser = \Auth::user()->creatorId();
        $user = App\Models\User::find($authUser);
    ?>

    <div class="row justify-content-center" id="printableArea">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="account-main-title mb-5">
                        <h5><?php echo e('Profit & Loss of ' . $user->name . ' as of ' . $filter['startDateRange'] . ' to ' . $filter['endDateRange']); ?>

                            </h4>
                    </div>
                    <div
                        class="aacount-title d-flex align-items-center justify-content-between border-top border-bottom py-2">
                        <h6 class="mb-0"><?php echo e(__('Account')); ?></h6>
                        <h6 class="mb-0 text-center"><?php echo e(__('Account Code')); ?></h6>
                        <h6 class="mb-0 text-end"><?php echo e(__('Total')); ?></h6>

                    </div>
                    <?php
                        $totalIncome = 0;
                        $netProfit = 0;
                        $totalCosts = 0;
                        $grossProfit= 0;
                    ?>

                    <?php $__currentLoopData = $chartAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accounts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($accounts['Type'] == 'Income'): ?>
                            <div class="account-main-inner border-bottom py-2">
                                <p class="fw-bold mb-2"><?php echo e($accounts['Type']); ?></p>

                                <?php $__currentLoopData = $accounts['account']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="account-inner d-flex align-items-center justify-content-between">
                                        <?php if(!preg_match('/\btotal\b/i', $record['account_name'])): ?>
                                            <p class="mb-2 ps-3"><a
                                                    href="<?php echo e(route('report.ledger', $record['account_id'])); ?>?account=<?php echo e($record['account_id']); ?>"
                                                    class="text-primary"><?php echo e($record['account_name']); ?></a>
                                            </p>
                                        <?php else: ?>
                                            <p class="fw-bold mb-2"><a
                                                    href="<?php echo e(route('report.ledger', $record['account_id'])); ?>?account=<?php echo e($record['account_id']); ?>"
                                                    class="text-dark"><?php echo e($record['account_name']); ?></a>
                                        <?php endif; ?>
                                        <p class="mb-2 text-center"><?php echo e($record['account_code']); ?></p>
                                        <p class="text-primary mb-2 float-end text-end">
                                            <?php echo e(\Auth::user()->priceFormat($record['netAmount'])); ?></p>
                                    </div>

                                    <?php
                                        if ($record['account_name'] === 'Total Income') {
                                            $totalIncome = $record['netAmount'];
                                        }

                                        if ($record['account_name'] == 'Total Costs of Goods Sold') {
                                            $totalCosts = $record['netAmount'];
                                        }
                                        $grossProfit = $totalIncome - $totalCosts;
                                    ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                        <?php if($accounts['Type'] == 'Costs of Goods Sold'): ?>
                        <div class="account-main-inner border-bottom py-2">
                            <p class="fw-bold mb-2"><?php echo e($accounts['Type']); ?></p>

                            <?php $__currentLoopData = $accounts['account']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                if($record['netAmount'] > 0)
                                {
                                    $netAmount = $record['netAmount'];
                                }
                                else {
                                    $netAmount = -$record['netAmount'];
                                }
                            ?>
                                <div class="account-inner d-flex align-items-center justify-content-between">
                                    <?php if(!preg_match('/\btotal\b/i', $record['account_name'])): ?>
                                        <p class="mb-2 ps-3"><a
                                                href="<?php echo e(route('report.ledger', $record['account_id'])); ?>?account=<?php echo e($record['account_id']); ?>"
                                                class="text-primary"><?php echo e($record['account_name']); ?></a>
                                        </p>
                                    <?php else: ?>
                                        <p class="fw-bold mb-2"><a
                                                href="<?php echo e(route('report.ledger', $record['account_id'])); ?>?account=<?php echo e($record['account_id']); ?>"
                                                class="text-dark"><?php echo e($record['account_name']); ?></a>
                                    <?php endif; ?>
                                    <p class="mb-2 text-center"><?php echo e($record['account_code']); ?></p>
                                    <p class="text-primary mb-2 float-end text-end">
                                        <?php echo e(\Auth::user()->priceFormat($netAmount)); ?></p>
                                </div>

                                <?php
                                    if ($record['account_name'] === 'Total Income') {
                                        $totalIncome = $record['netAmount'];
                                    }

                                    if ($record['account_name'] == 'Total Costs of Goods Sold') {
                                        $totalCosts = $netAmount;
                                    }
                                    $grossProfit = $totalIncome - ($totalCosts);
                                ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php if($grossProfit > 0): ?>
                    <div class="account-inner d-flex align-items-center justify-content-between border-bottom">
                        <p></p>
                        <p class="fw-bold mb-2 text-center"><?php echo e(__('Gross Profit')); ?></p>
                        <p class="text-primary mb-2 float-end text-end">
                            <?php echo e(\Auth::user()->priceFormat($grossProfit)); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php $__currentLoopData = $chartAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accounts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($accounts['Type'] == 'Expenses'): ?>
                            <div class="account-main-inner border-bottom py-2">
                                <p class="fw-bold mb-2"><?php echo e($accounts['Type']); ?></p>

                                <?php $__currentLoopData = $accounts['account']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                if($record['netAmount'] > 0)
                                {
                                    $netAmount = $record['netAmount'];
                                }
                                else {
                                    $netAmount = -$record['netAmount'];
                                }
                            ?>
                                    <div class="account-inner d-flex align-items-center justify-content-between">
                                        <?php if(!preg_match('/\btotal\b/i', $record['account_name'])): ?>
                                            <p class="mb-2 ps-3"><a
                                                    href="<?php echo e(route('report.ledger', $record['account_id'])); ?>?account=<?php echo e($record['account_id']); ?>"
                                                    class="text-primary"><?php echo e($record['account_name']); ?></a>
                                            </p>
                                        <?php else: ?>
                                            <p class="fw-bold mb-2"><a
                                                    href="<?php echo e(route('report.ledger', $record['account_id'])); ?>?account=<?php echo e($record['account_id']); ?>"
                                                    class="text-dark"><?php echo e($record['account_name']); ?></a>
                                        <?php endif; ?>
                                        <p class="mb-2 text-center"><?php echo e($record['account_code']); ?></p>
                                        <p class="text-primary mb-2 float-end text-end">
                                            <?php echo e(\Auth::user()->priceFormat($netAmount)); ?></p>
                                    </div>

                                    <?php
                                        if ($record['account_name'] === 'Total Expenses') {
                                            $totalIncome = $record['netAmount'];
                                            $netProfit = $grossProfit - $netAmount;
                                        }
                                    ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="account-inner d-flex align-items-center justify-content-between border-bottom">
                                <p></p>
                                <p class="fw-bold mb-2 text-center"><?php echo e(__('Net Profit/Loss')); ?></p>
                                <p class="text-primary mb-2 float-end text-end">
                                    <?php echo e(\Auth::user()->priceFormat($netProfit)); ?></p>
                                </div>
                                <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\erpone\resources\views/report/profit_loss.blade.php ENDPATH**/ ?>