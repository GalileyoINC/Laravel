<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Directive for data-column attribute
        Blade::directive('dataColumn', function ($expression) {
            return "<?php echo 'data-column=\"' . {$expression} . '\"'; ?>";
        });

        // Directive for data-value attribute
        Blade::directive('dataValue', function ($expression) {
            return "<?php echo 'data-value=\"' . {$expression} . '\"'; ?>";
        });

        // Directive for data-status attribute
        Blade::directive('dataStatus', function ($expression) {
            return "<?php echo 'data-status=\"' . {$expression} . '\"'; ?>";
        });

        // Directive for filter row cell
        Blade::directive('filterCell', function ($expression) {
            [$column, $type, $options] = array_pad(explode(',', $expression, 3), 3, null);

            $column = trim($column);
            $type = trim($type, " '\"");

            if ($type === 'text') {
                return "<?php echo '<td><input type=\"text\" class=\"form-control filter-input\" data-column=\"' . {$column} . '\"></td>'; ?>";
            }
            if ($type === 'select') {
                return "<?php echo '<td><select class=\"form-control filter-select\" data-column=\"' . {$column} . '\">' . {$options} . '</select></td>'; ?>";
            }
            if ($type === 'button') {
                return '<?php echo \'<td><button type="button" class="btn btn-sm btn-warning clear-filters-btn"><i class="fas fa-times"></i> Clear</button></td>\'; ?>';
            }

            return "<?php echo '<td></td>'; ?>";
        });
    }
}
