<?php $__env->startSection('content'); ?>
        <div class="content">
                <div class="container-fluid">
                        <div class="row">
                                <div class="col-lg-12">

                                        <template v-if="menu==0">
                                                <page-home></page-home>
                                        </template>

                                        <template v-if="menu==1">
                                                <page-departamentos></page-departamentos>
                                        </template>

                                        <template v-if="menu==2">
                                                <page-cargos></page-cargos>
                                        </template>

                                        <template v-if="menu==3">
                                                <page-vacantes></page-vacantes>
                                        </template>
                                        <template v-if="menu==4">
                                                <page-usuarios :auth="<?php echo e(Auth::User()); ?>"></page-usuarios>
                                        </template>

                                        <template v-if="menu==5">
                                                <page-roles :auth="<?php echo e(Auth::User()); ?>"></page-roles>
                                        </template>

                                        <template v-if="menu==6">
                                                <page-permisos :auth="<?php echo e(Auth::User()); ?>"></page-permisos>
                                        </template>

                                        <template v-if="menu==7">
                                                <page-postulantes :auth="<?php echo e(Auth::User()); ?>"></page-postulantes>
                                        </template>

                                        <template v-if="menu==8">
                                                <page-moderadores :auth="<?php echo e(Auth::User()); ?>"></page-moderadores>
                                        </template>

                                        <template v-if="menu==9">
                                                <page-curriculum :auth="<?php echo e(Auth::User()); ?>"></page-curriculum>
                                        </template>
                                        
                                        <template v-if="menu==10">
                                                <page-funcionario :auth="<?php echo e(Auth::User()); ?>"></page-funcionario>
                                        </template>
                                        <template v-if="menu==11">
                                                <page-form-documento :auth="<?php echo e(Auth::User()); ?>"></page-form-documento>
                                        </template>
                                        <template v-if="menu==12">
                                                <page-documento :auth="<?php echo e(Auth::User()); ?>"></page-documento>
                                        </template>
                                        <template v-if="menu==13">
                                                <page-archivador :auth="<?php echo e(Auth::User()); ?>"></page-archivador>
                                        </template>
                                </div>
                        </div>
                </div>
        </div>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.principal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/correspondencia/resources/views/contenido/contenido.blade.php ENDPATH**/ ?>