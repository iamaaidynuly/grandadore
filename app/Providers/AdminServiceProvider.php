<?php

namespace App\Providers;

use App\Services\PageManager\Facades\PageManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Spatie\Valuestore\Valuestore;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //region Components
        Blade::aliasComponent('admin.components.modal', 'modal');
        Blade::aliasComponent('admin.components.input', 'input');
        Blade::aliasComponent('admin.components.zselect', 'zselect');
        Blade::aliasComponent('admin.components.labelauty', 'labelauty');
        Blade::aliasComponent('admin.components.file', 'file');
        Blade::aliasComponent('admin.components.alink', 'alink');
        Blade::aliasComponent('admin.components.banner_block', 'bannerBlock');
        Blade::aliasComponent('admin.components.card', 'card');
        Blade::aliasComponent('admin.components.seo', 'seo');
        //endregion
        //region Directives
        Blade::directive('css', function ($expression) {
            return "<?php echo newCss($expression) ?>";
        });
        Blade::directive('js', function ($expression) {
            return "<?php echo newJs($expression) ?>";
        });
        Blade::directive('safe', function ($expression) {
            return "<?php echo safe($expression) ?>";
        });
        Blade::directive('banners', function ($expression) {
            return "<?php \$this_count=\$params[$expression]['count']??1; \$key=$expression; for(\$i=0; \$i<\$this_count; \$i++): ?>";
        });
        Blade::directive('endbanners', function () {
            return "<?php endfor; unset(\$i, \$key, \$this_count); ?>";
        });
        Blade::directive('cards', function ($expression) {
            return "<?php \$thisExpression=$expression; \$key=\$thisExpression['banners']; \$thisExpression['count']=\$params[\$key]['count']??1; \$__env->startComponent('admin.components.cards', \$thisExpression); for(\$i=0; \$i<\$thisExpression['count']; \$i++): \$__env->slot('tab_'.\$i)?>";
        });
        Blade::directive('endcards', function ($expression) {
            return "<?php \$__env->endSlot(); endfor; unset(\$i, \$key, \$thisExpression); echo \$__env->renderComponent()?>";
        });
        Blade::directive('banner', function ($expression) {
            return "<?php echo banner(\$params, \$banners, \$key??null, \$i??0, $expression) ?>";
        });
        Blade::directive('bylang', function ($expression) {
            return "<?php \$__env->startComponent('admin.components.bylang'" . ($expression ? ", $expression" : '') . "); foreach(\$isos as \$iso): \$__env->slot('bylang_'.\$iso) ?>";
        });
        Blade::directive('endbylang', function () {
            return "<?php \$__env->endSlot();endforeach;echo \$__env->renderComponent() ?>";
        });
        //endregion
        //region Includes
        Blade::include('admin.includes.ckeditor', 'ckeditor');
        //endregion
        //region Validator rules
        Validator::extend('is_url', function ($attribute, $value, $parameters, $validator) {
            return to_url($value) == $value;
        });
        Validator::extend('not_in_routes', function ($attribute, $value, $parameters, $validator) {
            return !PageManager::inUsedRoutes($value);
        });
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\+?([0-9][- ]*){8,}$/', $value);
        });
        Validator::extend('mail', function ($attribute, $value, $parameters, $validator) {
            return is_email($value);
        });
        //endregion
    }
}
