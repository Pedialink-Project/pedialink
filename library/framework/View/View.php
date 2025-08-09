<?php

namespace Library\Framework\View;

/**
 * Core view compile egine. Contains logic
 * of how to compile the view.php files to normal
 * .php files.
 * 
 * How it works?
 * It just replaces the preprocessor directives such
 * as @if() to their corresponding valid php code such as
 * <?php if () { ?>
 * 
 * NOTE for cs 28 members: To add more features 
 * to view.php files, you need to modify this class!
 * 
 * Right now this engine supports:
 * 
 * if-else, foreach, section, yield, extend,
 * include, echo, include.
 * 
 * Let's discuss in group if you want to add 
 * additional features or change existing features!
 */
class View
{
    /**
     * Path of view.php files
     * @var string
     */
    private string $viewsPath;

    /**
     * Path of cached view files
     * @var string
     */
    private string $cachePath;

    /**
     * Name of extension. Default is view.php
     * @var string
     */
    private string $extension;

    /**
     * Array to kep track of sections 
     * when compiling the view files
     * @var array
     */
    private array $sections = [];

    /**
     * Name of the currently compiling section
     * @var string
     */
    private string $currentSection = '';

    public function __construct(string $viewsPath, string $cachePath, string $extension)
    {
        $this->viewsPath = rtrim($viewsPath, '/');
        $this->cachePath = rtrim($cachePath, '/');
        $this->extension = rtrim($extension, '/');

    }

    /**
     * Compiles the final view, injects variables into view
     * and returns the html string
     * 
     * @param string $view Name of the view page relative to resources/views/pages directory
     * @param array $data Data to be passed to the view
     * @return bool|string Returns the html string
     */
    public function make(string $view, array $data = []): string
    {
        $compiled = $this->compileView($view);

        // Extract variables for use in the view
        extract($data, EXTR_SKIP);

        // Include the compiled view
        ob_start();
        include $compiled;
        return ob_get_clean();
    }

    /**
     * Echoes the return value of make() method. Can be useful
     * when you want to directly push to output buffer.
     * 
     * Note for CS 28: You will normally not use this and the
     * view() helper also relies on the normal make() method.
     * This method is only for emergency/testing purposes or
     * edge case scenarios!
     * 
     * @param string $view Name of the view page relative to resources/views/pages directory
     * @param array $data Data to be passed to the view
     * @return void
     */
    public function makeRaw(string $view, array $data = []): void
    {
        echo $this->make($view, $data);
    }

    /**
     * Encapsulates the logic of compiling view.php and 
     * caching final php files in the cache directory.
     * 
     * @param string $view Name of the view page relative to resources/views/pages directory
     * @return string
     */
    private function compileView(string $view): string
    {
        $viewPath = $this->viewsPath . '/' . str_replace('.', '/', $view) . '.' . $this->extension;
        $cachedPath = $this->cachePath . '/' . md5($viewPath) . '.php';

        // Compile only if cache doesn't exist or source file is newer
        if (!file_exists($cachedPath) || filemtime($cachedPath) < filemtime($viewPath)) {
            $content = file_get_contents($viewPath);
            $compiled = $this->compile($content);

            // store the newly compiled file in the cache directory
            file_put_contents($cachedPath, $compiled);
        }

        return $cachedPath;
    }

    /**
     * Starts the current section and turns on output buffering.
     * This method gets called at the line
     * where there is a @section in the view.php
     * 
     * For CS 28 members: Read more on output buffering here:
     * 
     * https://www.php.net/manual/en/function.ob-start.php
     * 
     * @param string $name Name of the section
     * @return void
     */
    public function startSection(string $name): void
    {
        $this->currentSection = $name;
        ob_start();
    }

    /**
     * Retreives the contents of the active buffer and turns of
     * output buffering. This method get called in the line
     * where is a @endsection in the view.php
     * 
     * For CS 28 members: Read more about this function here:
     * 
     * https://www.php.net/manual/en/function.ob-get-clean.php
     * 
     * @return void
     */
    public function stopSection(): void
    {
        $this->sections[$this->currentSection] = ob_get_clean();
        $this->currentSection = '';
    }

    /**
     * Places the section contents in the relavant @yield directive.
     * The @yield directives will be in the layout files and when other files
     * extend it, the @yield('name') will get replaced by the contents inside
     * the @section('name')
     * 
     * @param string $name Name of the section content to be replaced with
     * @param string $default Default content
     * @return bool|mixed|string
     */
    public function yieldContent(string $name, string $default = ''): string
    {
        return $this->sections[$name] ?? $default;
    }

    /**
     * Compiles the view.php to php files. This method is
     * responsible for translating all the directives such
     * as @if, @section, etc to their correct php counterparts
     * 
     * Example: @if() => <?php if() { ?>
     * 
     * NOTE for cs 28 members: The compiled views are in storage/cache!
     * Due to some weird workaround for permissions and docker volume 
     * you will not be able to see these files in your local environment.
     * Login to the docker environment and access them in the following
     * directory: /var/www/html/storage/cache
     * 
     * @param string $php view.php file content
     * @return array|string|null
     */
    private function compile(string $php): string
    {
        // Bootstraps the current class instance to $__env variable
        $php = "<?php \$__env = \$this; ?>\n" . $php;

        // Handles @extends directives
        $parent = null;
        if (preg_match('/@extends\(["\'](.+?)["\']\)/', $php, $m)) {
            $parent = $m[1];
            $php    = str_replace($m[0], '', $php);
        }

        // Handles @section directives
        $php = preg_replace(
            '/@section\(["\'](.+?)["\']\)/',
            '<?php $__env->startSection(\'$1\'); ?>',
            $php
        );

        // Handles @endsection directives
        $php = str_replace(
            '@endsection',
            '<?php $__env->stopSection(); ?>',
            $php
        );

        // Handles @yield directives
        $php = preg_replace(
            '/@yield\(["\'](.+?)["\']\)/',
            '<?php echo $__env->yieldContent(\'$1\'); ?>',
            $php
        );

        // Handles @include directives
        $php = preg_replace(
            '/@include\(["\'](.+?)["\']\)/',
            '<?php echo $this->make(\'$1\', get_defined_vars()); ?>',
            $php
        );

        // Handle component directives
        $php = preg_replace_callback(
            '/<c-([a-zA-Z0-9_.\-]+)\s*([^>]*)\/>/',
            function ($m) {
                // $m[1] = tag name, $m[2] = attribute string (may be empty)
                $tag = $m[1] ?? '';

                // replace nested folder with appropriate slashes
                $path = str_replace('.', '/', $tag);

                $attrString = isset($m[2]) ? trim($m[2]) : '';

                // parse attributes robustly: supports key="v", key='v', and boolean key
                $pairs = [];
                if (preg_match_all(
                    '/([a-zA-Z0-9_\-:]+)(?:\s*=\s*(?:"([^"]*)"|\'([^\']*)\'))?/',
                    $attrString,
                    $am,
                    PREG_SET_ORDER
                )) {
                    foreach ($am as $p) {
                        if (!is_array($p)) continue;
                        $key = $p[1] ?? '';
                        $val = null;
                        if (isset($p[2]) && $p[2] !== '') {
                            $val = $p[2];
                        } elseif (isset($p[3]) && $p[3] !== '') {
                            $val = $p[3];
                        }

                        if ($val !== null) {
                            $pairs[] = "'" . addslashes($key) . "' => '" . addslashes($val) . "'";
                        } else {
                            // boolean attribute (e.g. disabled)
                            $pairs[] = "'" . addslashes($key) . "' => true";
                        }
                    }
                }

                $attrArray = '[' . implode(', ', $pairs) . ']';

                // call make() with empty default slot and empty slots array
                return "<?php echo \$this->make('components.{$path}', array_merge({$attrArray}, ['slot'=>'', 'slots'=>[]])); ?>";
            },
            $php
        );

        $php = preg_replace_callback(
            '/<c-([a-zA-Z0-9_.\-]+)\s*([^>]*)>([\s\S]*?)<\/c-\1>/',
            function($m) {
                $tag = $m[1];
                
                // replace nested folder with appropriate slashes
                $path = str_replace('.', '/', $tag);
                
                $attrString = isset($m[2]) ? trim($m[2]) : '';

                // parse attributes into PHP array syntax
                $pairs = [];
                if (preg_match_all(
                    '/([a-zA-Z0-9_\-:]+)(?:\s*=\s*(?:"([^"]*)"|\'([^\']*)\'))?/',
                    $attrString,
                    $am,
                    PREG_SET_ORDER
                )) {
                    foreach ($am as $p) {
                        if (!is_array($p)) continue;
                        $key = $p[1] ?? '';
                        $val = null;
                        if (isset($p[2]) && $p[2] !== '') {
                            $val = $p[2];
                        } elseif (isset($p[3]) && $p[3] !== '') {
                            $val = $p[3];
                        }

                        if ($val !== null) {
                            $pairs[] = "'" . addslashes($key) . "' => '" . addslashes($val) . "'";
                        } else {
                            // boolean attribute (e.g. disabled)
                            $pairs[] = "'" . addslashes($key) . "' => true";
                        }
                    }
                }

                $attrArray = '[' . implode(',', $pairs) . ']';
                
                // compile inner slot recursively
                $inner = $this->compile($m[3]);
                return "<?php ob_start(); ?>\n" 
                    . $inner 
                    . "\n<?php \$__slot = ob_get_clean(); echo \$this->make('components." 
                    . $path 
                    . "', array_merge(" . $attrArray 
                    . ", ['slot'=>\$__slot])); ?>";
            },
            $php
        );


        // Handles @if, @elseif, @else, @endif directives
        $php = preg_replace('/@if\((.+?)\)/', '<?php if ($1): ?>', $php);
        $php = preg_replace('/@elseif\((.+?)\)/', '<?php elseif ($1): ?>', $php);
        $php = str_replace('@else', '<?php else: ?>', $php);
        $php = str_replace('@endif', '<?php endif; ?>', $php);

        // Handles @foreach, @endforeach directives
        $php = preg_replace('/@foreach\((.+?)\)/', '<?php foreach ($1): ?>', $php);
        $php = str_replace('@endforeach', '<?php endforeach; ?>', $php);

        // Converts {{ }} syntax to proper echo format. Used for printing php variables in html code
        $php = preg_replace(
            '/\{\{\s*(.+?)\s*\}\}/',
            '<?php echo htmlspecialchars($1, ENT_QUOTES, "UTF-8"); ?>',
            $php
        );

        // Extends the layout php files (if the current page is extending from a layout)
        if ($parent) {
            $php .= "\n<?php echo \$__env->make('$parent', get_defined_vars()); ?>";
        }

        return $php;
    }
    
}
