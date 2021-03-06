<?php

namespace Dero\GenSynth;

/**
 * GenSynth - Generic Syntax Highlighter
 *
 * Based on the GeSHi class but updated for 5.6+. The GenSynth class for
 * Generic Syntax Highlighting. Please refer to the documentation at
 * http://qbnz.com/highlighter/documentation.php for more information
 * about how to use this class.
 *
 *  GenSynth is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  GenSynth is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with GenSynth; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package        GenSynth
 * @subpackage     core
 * @author         Ryan Pallas <derokorian@gmail.com>
 * @copyright  (C) 2014-2015 Ryan Pallas
 * @license        http://gnu.org/copyleft/gpl.html GNU GPL
 *
 */
class GenSynth
{
    const VERSION = '2.0.0-rc1';

    /** Paths for GenSynth files */
    const ROOT = __DIR__ . DIRECTORY_SEPARATOR;
    const LANG_ROOT = self::ROOT . 'lang' . DIRECTORY_SEPARATOR;

    /** Whether or not to be paranoid about security */
    const SECURITY_PARANOID = true;

    /** Don't use line numbers */
    const OPT_LINE_NUMBERS_NONE = 1;
    /** show normal line numbers */
    const OPT_LINE_NUMBERS_NORMAL = 2;
    /** show fancy line numbers */
    const OPT_LINE_NUMBERS_FANCY = 4;
    /** Use nothing to surround the source */
    const OPT_HEADER_NONE = 8;
    /** Use a "div" to surround the source */
    const OPT_HEADER_DIV = 16;
    /** Use a "pre" to surround the source */
    const OPT_HEADER_PRE = 32;
    /** Use a pre to wrap lines when line numbers are enabled or to wrap the whole code. */
    const OPT_HEADER_PRE_VALID = 64;
    /**
     * Use a "table" to surround the source:
     *
     *  <table>
     *    <thead><tr><td colspan="2">$header</td></tr></thead>
     *    <tbody><tr><td><pre>$linenumbers</pre></td><td><pre>$code></pre></td></tr></tbody>
     *    <tfooter><tr><td colspan="2">$footer</td></tr></tfoot>
     *  </table>
     *
     * this is essentially only a workaround for Firefox, see sf#1651996 or take a look at
     * https://bugzilla.mozilla.org/show_bug.cgi?id=365805
     *
     * @note when linenumbers are disabled this is essentially the same as GenSynth::HEADER_PRE
     */
    const OPT_HEADER_PRE_TABLE = 128;
    /** leave keywords casing unchanged */
    const OPT_CAPS_NO_CHANGE = 256;
    /** change keywords to uppercase */
    const OPT_CAPS_UPPER = 512;
    /** change keywords to lowercase */
    const OPT_CAPS_LOWER = 1024;
    /** Links in the source in the :link state */
    const LINK = 0;
    /** Links in the source in the :hover state */
    const HOVER = 1;
    /** Links in the source in the :active state */
    const ACTIVE = 2;
    /** Links in the source in the :visited state */
    const VISITED = 3;
    /** Strict mode never applies (this is the most common) */
    const NEVER = 0;
    /** Strict mode *might* apply, and can be enabled or
     * disabled by {@link GenSynth->enable_strict_mode()}
     */
    const MAYBE = 1;
    /** Strict mode always applies */
    const ALWAYS = 2;
    /** The key of the regex array defining what to search for */
    const SEARCH = 0;
    /** The key of the regex array defining what bracket group in a
     * matched search to use as a replacement */
    const REPLACE = 1;
    /** The key of the regex array defining any modifiers to the regular expression */
    const MODIFIERS = 2;
    /** The key of the regex array defining what bracket group in a
     * matched search to put before the replacement */
    const BEFORE = 3;
    /** The key of the regex array defining what bracket group in a
     * matched search to put after the replacement */
    const AFTER = 4;
    /** The key of the regex array defining a custom keyword to use
     * for this regex's html tag class */
    const REGEX_CLASS = 5;
    /** Used in language files to mark comments */
    const COMMENTS = 0;
    /** Basic number format for integers */
    const NUMBER_INT_BASIC = 1;
    /** Enhanced number format for integers like seen in C */
    const NUMBER_INT_CSTYLE = 2;
    /** Number format to highlight binary numbers with a suffix "b" */
    const NUMBER_BIN_SUFFIX = 16;
    /** Number format to highlight binary numbers with a prefix % */
    const NUMBER_BIN_PREFIX_PERCENT = 32;              //Default integers \d+
    /** Number format to highlight binary numbers with a prefix 0b (C) */
    const NUMBER_BIN_PREFIX_0B = 64;             //Default C-Style \d+[lL]?
    /** Number format to highlight octal numbers with a leading zero */
    const NUMBER_OCT_PREFIX = 256;            //[01]+[bB]
    /** Number format to highlight octal numbers with a prefix 0o (logtalk) */
    const NUMBER_OCT_PREFIX_0O = 512;    //%[01]+
    /** Number format to highlight octal numbers with a leading @ (Used in HiSofts Devpac series). */
    const NUMBER_OCT_PREFIX_AT = 1024;         //0b[01]+
    /** Number format to highlight octal numbers with a suffix of o */
    const NUMBER_OCT_SUFFIX = 2048;           //0[0-7]+
    /** Number format to highlight hex numbers with a prefix 0x */
    const NUMBER_HEX_PREFIX = 4096;        //0[0-7]+
    /** Number format to highlight hex numbers with a prefix $ */
    const NUMBER_HEX_PREFIX_DOLLAR = 8192;       //@[0-7]+
    /** Number format to highlight hex numbers with a suffix of h */
    const NUMBER_HEX_SUFFIX = 16384;           //[0-7]+[oO]
    /** Number format to highlight floating-point numbers without support for scientific notation */
    const NUMBER_FLT_NONSCI = 65536;           //0x[0-9a-fA-F]+
    /** Number format to highlight floating-point numbers without support for scientific notation */
    const NUMBER_FLT_NONSCI_F = 131072;    //$[0-9a-fA-F]+
    /** Number format to highlight floating-point numbers with support for scientific notation (E) and optional leading zero */
    const NUMBER_FLT_SCI_SHORT = 262144;          //[0-9][0-9a-fA-F]*h
    /** Number format to highlight floating-point numbers with support for scientific notation (E) and required leading digit */
    const NUMBER_FLT_SCI_ZERO = 524288;          //\d+\.\d+
    const MAX_PCRE_SUBPATTERNS = 8192;       //\d+(\.\d+)?f
    const MAX_PCRE_LENGTH = 32768;      //\.\d+e\d+
    const ERROR_NO_SUCH_LANG = 'NO_SUCH_LANG';       //\d+(\.\d+)?e\d+

    // Some PCRE limitations that will cause fatals if exceeded
    const ERROR_FILE_NOT_READABLE = 'FILE_NOT_READABLE';
    const ERROR_INVALID_CAPS_TYPE = 'INVALID_CAPS_TYPE';

    // Possible error messages
    const ERROR_INVALID_HEADER_TYPE = 'INVALID_HEADER_TYPE';
    const ERROR_INVALID_LINE_NUMBER_TYPE = 'INVALID_LINE_NUMBER_TYPE';
    private $line_numbers_all = 7;
    private $header_all = 248;
    private $caps_all = 1792;
    /** @var array Translated error codes into messages */
    private $error_messages = [
        self::ERROR_NO_SUCH_LANG             => 'GenSynth could not find the language {LANGUAGE} (using path {PATH})',
        self::ERROR_FILE_NOT_READABLE        => 'The file specified for load_from_file was not readable',
        self::ERROR_INVALID_CAPS_TYPE        => 'The caps type specified is invalid',
        self::ERROR_INVALID_HEADER_TYPE      => 'The header type specified is invalid',
        self::ERROR_INVALID_LINE_NUMBER_TYPE => 'The line number type specified is invalid',
    ];

    /** @var string The source code to highlight */
    private $source = '';

    /** @var string The language to use when highlighting */
    private $language = '';

    /** @var array The data for the language used */
    private $language_data = [];

    /** @var string The error message associated with an error ks
     */
    private $error = false;

    /** @var boolean Whether highlighting is strict or not */
    private $strict_mode = false;

    /** @var boolean Whether to use CSS classes in output */
    private $use_classes = false;

    /** @var int the type of header to use */
    private $header_type = self::OPT_HEADER_PRE;


    /** @var array Array of permissions for which lexics should be highlighted */
    private $lexic_permissions = [
        'KEYWORDS'    => [],
        'COMMENTS'    => ['MULTI' => true],
        'REGEXPS'     => [],
        'ESCAPE_CHAR' => true,
        'BRACKETS'    => true,
        'SYMBOLS'     => false,
        'STRINGS'     => true,
        'NUMBERS'     => true,
        'METHODS'     => true,
        'SCRIPT'      => true,
    ];

    /** @var double The time it took to parse the code */
    private $time = 0;

    /** @var string The content of the header block */
    private $header_content = '';

    /** @var string The content of the footer block */
    private $footer_content = '';

    /** @var string The style of the header block */
    private $header_content_style = '';

    /** @var string The style of the footer block */
    private $footer_content_style = '';

    /**
     * Tells if a block around the highlighted source should be forced
     * if not using line numbering
     *
     * @var boolean
     */
    private $force_code_block = false;

    /** @var array The styles for hyperlinks in the code */
    private $link_styles = [];

    /** @var boolean Whether CSS IDs should be added to the code */
    private $add_ids = false;

    /** @var array Lines that should be highlighted extra */
    private $highlight_extra_lines = [];

    /** @var array Styles of lines that should be highlighted extra */
    private $highlight_extra_lines_styles = [];

    /** @var string Styles of extra-highlighted lines */
    private $highlight_extra_lines_style = 'background-color: #ffc;';

    /**
     * The line ending
     * If null, nl2br() will be used on the result string.
     * Otherwise, all instances of \n will be replaced with $line_ending
     *
     * @var string
     */
    private $line_ending = null;

    /** @var int Number at which line numbers should start at */
    private $line_numbers_start = 1;

    /** @var string The overall style for this code block */
    private $overall_style = 'font-family:monospace;';

    /** @var string  The style for the actual code */
    private $code_style = 'font: normal normal 1em/1.2em monospace; margin:0; padding:0; background:none; vertical-align:top;';

    /** @var string The overall class for this code block */
    private $overall_class = '';

    /** @var string The overall ID for this code block */
    private $overall_id = '';

    /** @var string Line number styles */
    private $line_style1 = 'font-weight: normal; vertical-align:top;';

    /** @var string Line number styles for fancy lines */
    private $line_style2 = 'font-weight: bold; vertical-align:top;';

    /** @var string Style for line numbers when GenSynth::HEADER_PRE_TABLE is chosen */
    private $table_linenumber_style = 'width:1px;text-align:right;margin:0;padding:0 2px;vertical-align:top;';

    /** @var boolean Flag for how line numbers are displayed */
    private $line_numbers = self::OPT_LINE_NUMBERS_NONE;

    /**
     * Flag to decide if multi line spans are allowed. Set it to false to make sure
     * each tag is closed before and reopened after each linefeed.
     *
     * @var boolean
     */
    private $allow_multiline_span = true;

    /** @var int The "nth" value for fancy line highlighting */
    private $line_nth_row = 0;

    /** @var int The size of tab stops */
    private $tab_width = 8;

    /** @var int Should we use language-defined tab stop widths? */
    private $use_language_tab_width = false;

    /** @var string Default target for keyword links */
    private $link_target = '';

    /**
     * The encoding to use for entity encoding
     * NOTE: Used with Escape Char Sequences to fix UTF-8 handling (cf. SF#2037598)
     *
     * @var string
     */
    private $encoding = 'utf-8';

    /** @var boolean Should keywords be linked? */
    private $keyword_links = true;

    /** @var string Currently loaded language file */
    private $loaded_language = '';

    /** @var bool Wether the caches needed for parsing are built or not */
    private $parse_cache_built = false;

    /**
     * Work around for Suhosin Patch with disabled /e modifier
     *
     * Note from suhosins author in config file:
     * <blockquote>
     *   The /e modifier inside <code>preg_replace()</code> allows code execution.
     *   Often it is the cause for remote code execution exploits. It is wise to
     *   deactivate this feature and test where in the application it is used.
     *   The developer using the /e modifier should be made aware that he should
     *   use <code>preg_replace_callback()</code> instead
     * </blockquote>
     *
     * @var array
     */
    private $_kw_replace_group = 0;
    private $_rx_key = 0;

    /**
     * some "callback parameters" for handle_multiline_regexps
     *
     * @var string
     */
    private $_hmr_before = '';
    private $_hmr_replace = '';
    private $_hmr_after = '';
    private $_hmr_key = 0;

    /**
     * @param string $source
     * @param string $language
     * @param int    $opt
     */
    public function __construct($source = '', $language = '', $opt = 0)
    {
        if (!empty($source)) {
            $this->setSource($source);
        }
        if (!empty($language)) {
            $this->setLanguage($language);
        }
        if ($this->line_numbers_all & $opt) {
            $this->setLineNumbers($this->line_numbers_all & $opt);
        }
        if ($this->header_all & $opt) {
            $this->setHeaderType($this->header_all & $opt);
        }
        if ($this->caps_all & $opt) {
            $this->setCaps($this->caps_all & $opt);
        }
    }

    /**
     * Sets the source code for this object
     *
     * @param string $source The source code to highlight
     */
    public function setSource($source)
    {
        $this->source = $source;
        $this->highlight_extra_lines = [];
    }

    /**
     * Easy way to highlight stuff. Behaves just like highlight_string
     *
     * @param  string $source
     * @param  string $language
     * @param  bool   $return [Default: false] *Can be skipped*
     * @param  int    $opts   [Default: 0]
     *
     * @return bool|string
     */
    public static function highlight_string($source, $language, $return = false, $opts = 0)
    {
        if (is_int($return)) {
            $opts = $return;
            $return = false;
        }

        $gs = new static($source, $language, $opts);
        $s = $gs->parseCode();

        if ($gs->error()) {
            return false;
        }

        if ($return) {
            return '<code>' . $s . '</code>';
        }
        echo '<code>' . $s . '</code>';

        return true;
    }

    /**
     * Easy way to highlight stuff. Behaves just like highlight_file
     *
     * @param  string $filename the full filepath of the file to highlight
     * @param  string $language language to use
     *                          (will try to determine from extension if not provided)
     *                          [Default: ''] *Can be skipped*
     * @param  bool   $return   [Default: false] *Can be skipped*
     * @param  int    $opts     [Default: 0]
     *
     * @return bool|string
     */
    public static function highlight_file($filename, $language = '', $return = false, $opts = 0)
    {
        if (is_bool($language)) {
            !is_int($return) ?: $opts = $return;
            $return = $language;
            $language = '';
        }
        elseif (is_int($language)) {
            $opts = $language;
            $language = '';
        }

        if (file_exists($filename) && is_readable($filename)) {
            $gs = new static($filename, '', $opts);

            if ($language == '') {
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $language = $gs->get_language_name_from_extension($ext);
            }

            $gs->setLanguage($language);
            $s = $gs->parseCode();

            if ($gs->error()) {
                return false;
            }

            if ($return) {
                return '<code>' . $s . '</code>';
            }
            echo '<code>' . $s . '</code>';

            return true;
        }

        return false;
    }

    /**
     * Sets the language for this object
     *
     * @param string $language    The name of the language to use
     * @param bool   $force_reset reset any loaded language
     */
    public function setLanguage($language, $force_reset = false)
    {
        if ($force_reset) {
            $this->loaded_language = false;
        }

        // Clean up the language name to prevent malicious code injection
        $language = preg_replace('#[^a-zA-Z0-9\-_]#', '', $language);

        $language = strtolower($language);

        // Retrieve the full filename
        $file_name = self::LANG_ROOT . $language . '.php';
        if ($file_name == $this->loaded_language) {
            // this language is already loaded!
            return;
        }

        $this->language = $language;

        $this->error = false;
        $this->strict_mode = self::NEVER;

        //Check if we can read the desired file
        if (!is_readable($file_name)) {
            $this->error = self::ERROR_NO_SUCH_LANG;

            return;
        }

        // Load the language for parsing
        $this->load_language($file_name);
    }

    /**
     * Gets language information and stores it for later use
     *
     * @param string $file_name The filename of the language file you want to load
     *
     * @todo Needs to load keys for lexic permissions for keywords, regexps etc
     */
    private function load_language($file_name)
    {
        if ($file_name == $this->loaded_language) {
            // this file is already loaded!
            return;
        }

        //Prepare some stuff before actually loading the language file
        $this->loaded_language = $file_name;
        $this->parse_cache_built = false;
        $this->enable_highlighting();
        $language_data = [];

        //Load the language file
        require $file_name;

        // Perhaps some checking might be added here later to check that
        // $language data is a valid thing but maybe not
        $this->language_data = $language_data;

        // Set strict mode if should be set
        $this->strict_mode = $this->language_data['STRICT_MODE_APPLIES'];

        // Set permissions for all lexics to true
        // so they'll be highlighted by default
        foreach (array_keys($this->language_data['KEYWORDS']) as $key) {
            if (!empty($this->language_data['KEYWORDS'][$key])) {
                $this->lexic_permissions['KEYWORDS'][$key] = true;
            }
            else {
                $this->lexic_permissions['KEYWORDS'][$key] = false;
            }
        }

        foreach (array_keys($this->language_data['COMMENT_SINGLE']) as $key) {
            $this->lexic_permissions['COMMENTS'][$key] = true;
        }
        foreach (array_keys($this->language_data['REGEXPS']) as $key) {
            $this->lexic_permissions['REGEXPS'][$key] = true;
        }

        if (!empty($this->language_data['PARSER_CONTROL']['ENABLE_FLAGS'])) {
            foreach ($this->language_data['PARSER_CONTROL']['ENABLE_FLAGS'] as $flag => $value) {
                // it's either true or false and maybe is true as well
                $perm = $value !== self::NEVER;
                if ($flag == 'ALL') {
                    $this->enable_highlighting($perm);
                    continue;
                }
                if (!isset($this->lexic_permissions[$flag])) {
                    // unknown lexic permission
                    continue;
                }
                if (is_array($this->lexic_permissions[$flag])) {
                    foreach ($this->lexic_permissions[$flag] as $key => $val) {
                        $this->lexic_permissions[$flag][$key] = $perm;
                    }
                }
                else {
                    $this->lexic_permissions[$flag] = $perm;
                }
            }
            unset($this->language_data['PARSER_CONTROL']['ENABLE_FLAGS']);
        }

        //Fix: Problem where hardescapes weren't handled if no ESCAPE_CHAR was given
        //You need to set one for HARDESCAPES only in this case.
        if (!isset($this->language_data['HARDCHAR'])) {
            $this->language_data['HARDCHAR'] = $this->language_data['ESCAPE_CHAR'];
        }

        //NEW in 1.0.8: Allow styles to be loaded from a separate file to override defaults
        $style_filename = substr($file_name, 0, -4) . '.style.php';
        if (is_readable($style_filename)) {
            //Clear any style_data that could have been set before ...
            if (isset($style_data)) {
                unset($style_data);
            }

            //Read the Style Information from the style file
            include $style_filename;

            //Apply the new styles to our current language styles
            if (isset($style_data) && is_array($style_data)) {
                $this->language_data['STYLES'] =
                    array_merge_recursive($this->language_data['STYLES'], $style_data);
            }
        }
    }

    /**
     * Enables all highlighting
     *
     * The optional flag parameter was added in version 1.0.7.21 and can be used
     * to enable (true) or disable (false) all highlighting.
     *
     * @param boolean A flag specifying whether to enable or disable all highlighting
     *
     * @todo  Rewrite with array traversal
     */
    function enable_highlighting($flag = true)
    {
        $flag = $flag ? true : false;
        foreach ($this->lexic_permissions as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $this->lexic_permissions[$key][$k] = $flag;
                }
            }
            else {
                $this->lexic_permissions[$key] = $flag;
            }
        }
    }

    /**
     * Sets whether line numbers should be displayed.
     *
     * Valid values for the first parameter are:
     *
     *  - GenSynth::NO_LINE_NUMBERS: Line numbers will not be displayed
     *  - GenSynth::NORMAL_LINE_NUMBERS: Line numbers will be displayed
     *  - GenSynth::FANCY_LINE_NUMBERS: Fancy line numbers will be displayed
     *
     * For fancy line numbers, the second parameter is used to signal which lines
     * are to be fancy. For example, if the value of this parameter is 5 then every
     * 5th line will be fancy.
     *
     * @param int $flag How line numbers should be displayed
     * @param int $row  Defines which lines are fancy
     */
    public function setLineNumbers($flag, $row = 5)
    {
        if (!($flag & $this->line_numbers_all)) {
            $this->error = self::ERROR_INVALID_LINE_NUMBER_TYPE;
        }
        else {
            $this->line_numbers = $flag;
            $this->line_nth_row = $row;
        }
    }

    /**
     * Sets the type of header to be used.
     *
     * If GenSynth::HEADER_DIV is used, the code is surrounded in a "div".This
     * means more source code but more control over tab width and line-wrapping.
     * GenSynth::HEADER_PRE means that a "pre" is used - less source, but less
     * control. Default is GenSynth::HEADER_PRE. You can use GenSynth::HEADER_NONE
     * to specify that no header code should be outputted.
     *
     * @param int $flag The type of header to be used
     */
    public function setHeaderType($flag)
    {
        //Check if we got a valid header type
        if (!($this->header_all & $flag)) {
            $this->error = self::ERROR_INVALID_HEADER_TYPE;
        }
        else {
            $this->header_type = $flag;
        }
    }

    /**
     * Sets the case that keywords should use when found. Use the constants:
     *
     *  - GenSynth::CAPS_NO_CHANGE: leave keywords as-is
     *  - GenSynth::CAPS_UPPER: convert all keywords to uppercase where found
     *  - GenSynth::CAPS_LOWER: convert all keywords to lowercase where found
     *
     * @param int $flag A constant specifying what to do with matched keywords
     */
    public function setCaps($flag)
    {
        if (!($flag & $this->caps_all)) {
            $this->error = self::ERROR_INVALID_CAPS_TYPE;
        }
        else {
            $this->language_data['CASE_KEYWORDS'] = $flag;
        }
    }

    /**
     * Gets the version number
     *
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Returns an error message associated with the last GenSynth operation
     *
     * @return string|false An error message if there has been an error, else false
     */
    public function error()
    {
        if ($this->error) {
            $debug_tpl_vars = [
                '{LANGUAGE}' => $this->language,
                '{PATH}'     => self::LANG_ROOT,
            ];
            $msg = strtr($this->error_messages[$this->error], $debug_tpl_vars);

            return "<br /><strong>GenSynth Error:</strong> $msg (code {$this->error})<br />";
        }

        return false;
    }

    /**
     * Gets a human-readable language name
     *
     * @return string The name for the current language
     */
    public function getLanguageName()
    {
        if (self::ERROR_NO_SUCH_LANG == $this->error) {
            return $this->language_data['LANG_NAME'] . ' (Unknown Language)';
        }

        return $this->language_data['LANG_NAME'];
    }

    /**
     * Get supported langs or an associative array lang=>full_name.
     *
     * @param boolean $full_names
     *
     * @return array
     */
    public function getSupportedLanguages($full_names = false)
    {
        // return array
        $back = [];

        // we walk the lang root
        $dir = dir(self::LANG_ROOT);

        // foreach entry
        while (false !== ($entry = $dir->read())) {
            $full_path = self::LANG_ROOT . $entry;

            // Skip all dirs
            if (is_dir($full_path)) {
                continue;
            }

            // we only want lang.php files
            if (!preg_match('/^([^.]+)\.php$/', $entry, $matches)) {
                continue;
            }

            // Raw lang name is here
            $langname = $matches[1];

            // We want the fullname too?
            if ($full_names === true) {
                if (false !== ($fullname = $this->getLanguageFullname($langname))) {
                    $back[$langname] = $fullname; // we go associative
                }
            }
            else {
                // just store raw langname
                $back[] = $langname;
            }
        }

        $dir->close();

        return $back;
    }

    /**
     * Get full_name for a lang or false.
     *
     * @param string $language short langname (html4strict for example)
     *
     * @return mixed
     */
    public function getLanguageFullname($language)
    {
        //Clean up the language name to prevent malicious code injection
        $language = preg_replace('#[^a-zA-Z0-9\-_]#', '', $language);

        $language = strtolower($language);

        // get fullpath-filename for a langname
        $fullpath = self::LANG_ROOT . $language . '.php';

        // we need to get contents :S
        if (false === ($data = file_get_contents($fullpath))) {
            $this->error = sprintf('%s->%s Unknown Language: %s', __CLASS__, __METHOD__, $language);

            return false;
        }

        // match the langname
        if (!preg_match('/\'LANG_NAME\'\s*=>\s*\'((?:[^\']|\\\')+?)\'/', $data, $matches)) {
            $this->error = sprintf('%s->%s(%s): Regex can not detect language', __CLASS__, __METHOD__, $language);

            return false;
        }

        // return fullname for langname
        return stripcslashes($matches[1]);
    }

    /**
     * Sets the styles for the code that will be outputted
     * when this object is parsed. The style should be a
     * string of valid stylesheet declarations
     *
     * @param string  $style             The overall style for the outputted code block
     * @param boolean $preserve_defaults Whether to merge the styles with the current styles or not
     */
    public function setOverallStyle($style, $preserve_defaults = false)
    {
        if (!$preserve_defaults) {
            $this->overall_style = $style;
        }
        else {
            $this->overall_style .= " $style";
        }
    }

    /**
     * Sets the overall classname for this block of code. This
     * class can then be used in a stylesheet to style this object's
     * output
     *
     * @param string $class The class name to use for this block of code
     */
    public function setOverallClass($class)
    {
        $this->overall_class = $class;
    }

    /**
     * Sets the overall id for this block of code. This id can then
     * be used in a stylesheet to style this object's output
     *
     * @param string $id The ID to use for this block of code
     */
    public function setOverallId($id)
    {
        $this->overall_id = $id;
    }

    /**
     * Sets whether CSS classes should be used to highlight the source. Default
     * is off, calling this method with no arguments will turn it on
     *
     * @param boolean $flag Whether to turn classes on or not
     */
    public function setUseClasses($flag = true)
    {
        $this->use_classes = (bool) $flag;
    }

    /**
     * Sets the style for the actual code. This should be a string
     * containing valid stylesheet declarations. If $preserve_defaults is
     * true, then styles are merged with the default styles, with the
     * user defined styles having priority
     *
     * Note: Use this method to override any style changes you made to
     * the line numbers if you are using line numbers, else the line of
     * code will have the same style as the line number! Consult the
     * GenSynth documentation for more information about this.
     *
     * @param string  $style             The style to use for actual code
     * @param boolean $preserve_defaults Whether to merge the current styles with the new styles
     */
    public function setCodeStyle($style, $preserve_defaults = false)
    {
        if (!$preserve_defaults) {
            $this->code_style = $style;
        }
        else {
            $this->code_style .= " $style";
        }
    }

    /**
     * Sets the styles for the line numbers.
     *
     * @param string         $style1            The style for the line numbers that are "normal"
     * @param string|boolean $style2            If a string, this is the style of the line
     *                                          numbers that are "fancy", otherwise if boolean then this
     *                                          defines whether the normal styles should be merged with the
     *                                          new normal styles or not
     * @param boolean        $preserve_defaults If set, is the flag for whether to merge the "fancy"
     *                                          styles with the current styles or not
     */
    public function setLineStyle($style1, $style2 = '', $preserve_defaults = false)
    {
        //Check if we got 2 or three parameters
        if (is_bool($style2)) {
            $preserve_defaults = $style2;
            $style2 = '';
        }

        //Actually set the new styles
        if (!$preserve_defaults) {
            $this->line_style1 = $style1;
            $this->line_style2 = $style2;
        }
        else {
            $this->line_style1 .= " $style1";
            $this->line_style2 .= " $style2";
        }
    }

    /**
     * Sets wether spans and other HTML markup generated by GenSynth can
     * span over multiple lines or not. Defaults to true to reduce overhead.
     * Set it to false if you want to manipulate the output or manually display
     * the code in an ordered list.
     *
     * @param boolean $flag Whether multiline spans are allowed or not
     */
    public function enable_multiline_span($flag)
    {
        $this->allow_multiline_span = (bool) $flag;
    }

    /**
     * Get current setting for multiline spans, see GenSynth->enable_multiline_span().
     *
     * @see enable_multiline_span
     * @return bool
     */
    function get_multiline_span()
    {
        return $this->allow_multiline_span;
    }

    /**
     * Sets the style for a keyword group. If $preserve_defaults is
     * true, then styles are merged with the default styles, with the
     * user defined styles having priority
     *
     * @param int     $key               The key of the keyword group to change the styles of
     * @param string  $style             The style to make the keywords
     * @param boolean $preserve_defaults Whether to merge the new styles with the old or just
     *                                   to overwrite them
     */
    function set_keyword_group_style($key, $style, $preserve_defaults = false)
    {
        //Set the style for this keyword group
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['KEYWORDS'][$key] = $style;
        }
        else {
            $this->language_data['STYLES']['KEYWORDS'][$key] .= " $style";
        }

        //Update the lexic permissions
        if (!isset($this->lexic_permissions['KEYWORDS'][$key])) {
            $this->lexic_permissions['KEYWORDS'][$key] = true;
        }
    }

    /**
     * Turns highlighting on/off for a keyword group
     *
     * @param int     $key  The key of the keyword group to turn on or off
     * @param boolean $flag Whether to turn highlighting for that group on or off
     */
    function set_keyword_group_highlighting($key, $flag = true)
    {
        $this->lexic_permissions['KEYWORDS'][$key] = (bool) $flag;
    }

    /**
     * Sets the styles for comment groups.  If $preserve_defaults is
     * true, then styles are merged with the default styles, with the
     * user defined styles having priority
     *
     * @param int     $key               The key of the comment group to change the styles of
     * @param string  $style             The style to make the comments
     * @param boolean $preserve_defaults Whether to merge the new styles with the
     *                                   old or just to overwrite them
     */
    function set_comments_style($key, $style, $preserve_defaults = false)
    {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['COMMENTS'][$key] = $style;
        }
        else {
            $this->language_data['STYLES']['COMMENTS'][$key] .= " $style";
        }
    }

    /**
     * Turns highlighting on/off for comment groups
     *
     * @param int     $key  The key of the comment group to turn on or off
     * @param boolean $flag Whether to turn highlighting for that group on or off
     */
    function set_comments_highlighting($key, $flag = true)
    {
        $this->lexic_permissions['COMMENTS'][$key] = (bool) $flag;
    }

    /**
     * Sets the styles for escaped characters. If $preserve_defaults is
     * true, then styles are merged with the default styles, with the
     * user defined styles having priority
     *
     * @param string  $style             The style to make the escape characters
     * @param boolean $preserve_defaults Whether to merge the new styles with the old or just
     *                                   to overwrite them
     * @param int     $group             The array offset to set the style for
     */
    function set_escape_characters_style($style, $preserve_defaults = false, $group = 0)
    {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['ESCAPE_CHAR'][$group] = $style;
        }
        else {
            $this->language_data['STYLES']['ESCAPE_CHAR'][$group] .= " $style";
        }
    }

    /**
     * Turns highlighting on/off for escaped characters
     *
     * @param boolean $flag Whether to turn highlighting for escape characters on or off
     */
    function set_escape_characters_highlighting($flag = true)
    {
        $this->lexic_permissions['ESCAPE_CHAR'] = (bool) $flag;
    }

    /**
     * Sets the styles for symbols. If $preserve_defaults is
     * true, then styles are merged with the default styles, with the
     * user defined styles having priority
     *
     * @param string  The style to make the symbols
     * @param boolean Whether to merge the new styles with the old or just
     *                to overwrite them
     * @param int     Tells the group of symbols for which style should be set.
     */
    function set_symbols_style($style, $preserve_defaults = false, $group = 0)
    {
        // Update the style of symbols
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['SYMBOLS'][$group] = $style;
        }
        else {
            $this->language_data['STYLES']['SYMBOLS'][$group] .= " $style";
        }
    }

    /**
     * Turns highlighting on/off for symbols
     *
     * @param boolean Whether to turn highlighting for symbols on or off
     */
    function set_symbols_highlighting($flag)
    {
        // Update lexic permissions for this symbol group
        $this->lexic_permissions['SYMBOLS'] = (bool) $flag;
    }

    /**
     * Sets the styles for strings. If $preserve_defaults is
     * true, then styles are merged with the default styles, with the
     * user defined styles having priority
     *
     * @param string  The style to make the escape characters
     * @param boolean Whether to merge the new styles with the old or just
     *                to overwrite them
     * @param int     Tells the group of strings for which style should be set.
     */
    function set_strings_style($style, $preserve_defaults = false, $group = 0)
    {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['STRINGS'][$group] = $style;
        }
        else {
            $this->language_data['STYLES']['STRINGS'][$group] .= " $style";
        }
    }

    /**
     * Turns highlighting on/off for strings
     *
     * @param boolean Whether to turn highlighting for strings on or off
     */
    function set_strings_highlighting($flag)
    {
        $this->lexic_permissions['STRINGS'] = (bool) $flag;
    }

    /**
     * Sets the styles for strict code blocks. If $preserve_defaults is
     * true, then styles are merged with the default styles, with the
     * user defined styles having priority
     *
     * @param string  The style to make the script blocks
     * @param boolean Whether to merge the new styles with the old or just
     *                to overwrite them
     * @param int     Tells the group of script blocks for which style should be set.
     */
    function set_script_style($style, $preserve_defaults = false, $group = 0)
    {
        // Update the style of symbols
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['SCRIPT'][$group] = $style;
        }
        else {
            $this->language_data['STYLES']['SCRIPT'][$group] .= " $style";
        }
    }

    /**
     * Sets the styles for numbers. If $preserve_defaults is
     * true, then styles are merged with the default styles, with the
     * user defined styles having priority
     *
     * @param string  The style to make the numbers
     * @param boolean Whether to merge the new styles with the old or just
     *                to overwrite them
     * @param int     Tells the group of numbers for which style should be set.
     */
    function set_numbers_style($style, $preserve_defaults = false, $group = 0)
    {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['NUMBERS'][$group] = $style;
        }
        else {
            $this->language_data['STYLES']['NUMBERS'][$group] .= " $style";
        }
    }

    /**
     * Turns highlighting on/off for numbers
     *
     * @param boolean Whether to turn highlighting for numbers on or off
     */
    function set_numbers_highlighting($flag)
    {
        $this->lexic_permissions['NUMBERS'] = (bool) $flag;
    }

    /**
     * Sets the styles for methods. $key is a number that references the
     * appropriate "object splitter" - see the language file for the language
     * you are highlighting to get this number. If $preserve_defaults is
     * true, then styles are merged with the default styles, with the
     * user defined styles having priority
     *
     * @param int     The key of the object splitter to change the styles of
     * @param string  The style to make the methods
     * @param boolean Whether to merge the new styles with the old or just
     *                to overwrite them
     */
    function set_methods_style($key, $style, $preserve_defaults = false)
    {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['METHODS'][$key] = $style;
        }
        else {
            $this->language_data['STYLES']['METHODS'][$key] .= " $style";
        }
    }

    /**
     * Turns highlighting on/off for methods
     *
     * @param boolean Whether to turn highlighting for methods on or off
     */
    function set_methods_highlighting($flag)
    {
        $this->lexic_permissions['METHODS'] = (bool) $flag;
    }

    /**
     * Sets the styles for regexps. If $preserve_defaults is
     * true, then styles are merged with the default styles, with the
     * user defined styles having priority
     *
     * @param string  The style to make the regular expression matches
     * @param boolean Whether to merge the new styles with the old or just
     *                to overwrite them
     */
    function set_regexps_style($key, $style, $preserve_defaults = false)
    {
        if (!$preserve_defaults) {
            $this->language_data['STYLES']['REGEXPS'][$key] = $style;
        }
        else {
            $this->language_data['STYLES']['REGEXPS'][$key] .= " $style";
        }
    }

    /**
     * Turns highlighting on/off for regexps
     *
     * @param int     The key of the regular expression group to turn on or off
     * @param boolean Whether to turn highlighting for the regular expression group on or off
     */
    function set_regexps_highlighting($key, $flag)
    {
        $this->lexic_permissions['REGEXPS'][$key] = ($flag) ? true : false;
    }

    /**
     * Sets whether a set of keywords are checked for in a case sensitive manner
     *
     * @param int     The key of the keyword group to change the case sensitivity of
     * @param boolean Whether to check in a case sensitive manner or not
     */
    function set_case_sensitivity($key, $case)
    {
        $this->language_data['CASE_SENSITIVE'][$key] = ($case) ? true : false;
    }

    /**
     * Sets how many spaces a tab is substituted for
     *
     * Widths below zero are ignored
     *
     * @param int The tab width
     */
    function set_tab_width($width)
    {
        $this->tab_width = intval($width);

        //Check if it fit's the constraints:
        if ($this->tab_width < 1) {
            //Return it to the default
            $this->tab_width = 8;
        }
    }

    /**
     * Sets whether or not to use tab-stop width specifed by language
     *
     * @param boolean Whether to use language-specific tab-stop widths
     */
    function set_use_language_tab_width($use)
    {
        $this->use_language_tab_width = (bool) $use;
    }

    /**
     * Enables/disables strict highlighting. Default is off, calling this
     * method without parameters will turn it on. See documentation
     * for more details on strict mode and where to use it.
     *
     * @param boolean Whether to enable strict mode or not
     */
    function enable_strict_mode($mode = true)
    {
        if (self::MAYBE == $this->language_data['STRICT_MODE_APPLIES']) {
            $this->strict_mode = ($mode) ? self::ALWAYS : self::NEVER;
        }
    }

    /**
     * Given a file name, this method loads its contents in, and attempts
     * to set the language automatically. An optional lookup table can be
     * passed for looking up the language name. If not specified a default
     * table is used
     *
     * The language table is in the form
     * <pre>array(
     *   'lang_name' => array('extension', 'extension', ...),
     *   'lang_name' ...
     * );</pre>
     *
     * @param string The filename to load the source from
     * @param array  A lookup array to use instead of the default one
     */
    function load_from_file($file_name, $lookup = [])
    {
        if (is_readable($file_name)) {
            $this->setSource(file_get_contents($file_name));
            $this->setLanguage($this->get_language_name_from_extension(substr(strrchr($file_name, '.'), 1), $lookup));
        }
        else {
            $this->error = self::ERROR_FILE_NOT_READABLE;
        }
    }

    /**
     * Given a file extension, this method returns either a valid GenSynth language
     * name, or the empty string if it couldn't be found
     *
     * @param string The extension to get a language name for
     * @param array  A lookup array to use instead of the default one
     *
     * @return string
     */
    function get_language_name_from_extension($ext, $lookup = [])
    {
        $ext = strtolower($ext);

        if (!is_array($lookup) || empty($lookup)) {
            $lookup = [
                'as'      => 'actionscript',
                'as3'     => 'actionscript3',
                'conf'    => 'apache',
                'asp'     => 'asp',
                'sh'      => 'bash',
                'bf'      => 'bf',
                'c'       => 'c',
                'h'       => 'c',
                'cbl'     => 'cobol',
                'cpp'     => 'cpp',
                'hpp'     => 'cpp',
                'C'       => 'cpp',
                'H'       => 'cpp',
                'CPP'     => 'cpp',
                'HPP'     => 'cpp',
                'cs'      => 'csharp',
                'css'     => 'css',
                'bat'     => 'dos',
                'cmd'     => 'dos',
                'html'    => 'html',
                'htm'     => 'html',
                'ini'     => 'ini',
                'desktop' => 'ini',
                'java'    => 'java',
                'js'      => 'javascript',
                'sql'     => 'mysql',
                'pl'      => 'perl',
                'pm'      => 'perl',
                'php'     => 'php',
                'php5'    => 'php',
                'phtml'   => 'php',
                'phps'    => 'php',
                'py'      => 'python',
                'rb'      => 'ruby',
                'txt'     => 'text',
                'bas'     => 'vb',
                'vb'      => 'vbnet',
                'xml'     => 'xml',
                'svg'     => 'xml',
                'xrc'     => 'xml',
            ];
        }

        return $lookup[$ext] ?: 'text';
    }

    /**
     * Adds a keyword to a keyword group for highlighting
     *
     * @param int    The key of the keyword group to add the keyword to
     * @param string The word to add to the keyword group
     */
    function add_keyword($key, $word)
    {
        if (!is_array($this->language_data['KEYWORDS'][$key])) {
            $this->language_data['KEYWORDS'][$key] = [];
        }
        if (!in_array($word, $this->language_data['KEYWORDS'][$key])) {
            $this->language_data['KEYWORDS'][$key][] = $word;

            //NEW in 1.0.8 don't recompile the whole optimized regexp, simply append it
            if ($this->parse_cache_built) {
                $subkey = count($this->language_data['CACHED_KEYWORD_LISTS'][$key]) - 1;
                $this->language_data['CACHED_KEYWORD_LISTS'][$key][$subkey] .= '|' . preg_quote($word, '/');
            }
        }
    }

    /**
     * Removes a keyword from a keyword group
     *
     * @param int    The key of the keyword group to remove the keyword from
     * @param string The word to remove from the keyword group
     * @param bool   Wether to automatically recompile the optimized regexp list or not.
     *               Note: if you set this to false and @see GenSynth->parse_code() was already called once,
     *               for the current language, you have to manually call @see GenSynth->optimize_keyword_group()
     *               or the removed keyword will stay in cache and still be highlighted! On the other hand
     *               it might be too expensive to recompile the regexp list for every removal if you want to
     *               remove a lot of keywords.
     */
    function remove_keyword($key, $word, $recompile = true)
    {
        $key_to_remove = array_search($word, $this->language_data['KEYWORDS'][$key]);
        if ($key_to_remove !== false) {
            unset($this->language_data['KEYWORDS'][$key][$key_to_remove]);

            //NEW in 1.0.8, optionally recompile keyword group
            if ($recompile && $this->parse_cache_built) {
                $this->optimize_keyword_group($key);
            }
        }
    }

    /**
     * compile optimized regexp list for keyword group
     *
     * @param int   The key of the keyword group to compile & optimize
     */
    function optimize_keyword_group($key)
    {
        $this->language_data['CACHED_KEYWORD_LISTS'][$key] =
            $this->optimize_regexp_list($this->language_data['KEYWORDS'][$key]);
        $space_as_whitespace = false;
        if (isset($this->language_data['PARSER_CONTROL'])) {
            if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS'])) {
                if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS']['SPACE_AS_WHITESPACE'])) {
                    $space_as_whitespace = $this->language_data['PARSER_CONTROL']['KEYWORDS']['SPACE_AS_WHITESPACE'];
                }
                if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS'][$key]['SPACE_AS_WHITESPACE'])) {
                    if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS'][$key]['SPACE_AS_WHITESPACE'])) {
                        $space_as_whitespace = $this->language_data['PARSER_CONTROL']['KEYWORDS'][$key]['SPACE_AS_WHITESPACE'];
                    }
                }
            }
        }
        if ($space_as_whitespace) {
            foreach ($this->language_data['CACHED_KEYWORD_LISTS'][$key] as $rxk => $rxv) {
                $this->language_data['CACHED_KEYWORD_LISTS'][$key][$rxk] =
                    str_replace(" ", "\\s+", $rxv);
            }
        }
    }

    /**
     * this functions creates an optimized regular expression list
     * of an array of strings.
     *
     * Example:
     * <code>$list = array('faa', 'foo', 'foobar');
     *          => string 'f(aa|oo(bar)?)'</code>
     *
     * @param array  $list             array of (unquoted) strings
     * @param string $regexp_delimiter your regular expression delimiter, @see preg_quote()
     *
     * @return string for regular expression
     */
    private function optimize_regexp_list($list, $regexp_delimiter = '/')
    {
        $regex_chars = ['.', '\\', '+', '-', '*', '?', '[', '^', ']', '$',
            '(', ')', '{', '}', '=', '!', '<', '>', '|', ':', $regexp_delimiter];
        sort($list);
        $regexp_list = [''];
        $num_subpatterns = 0;
        $list_key = 0;

        // the tokens which we will use to generate the regexp list
        $tokens = [];
        $prev_keys = [];
        // go through all entries of the list and generate the token list
        $cur_len = 0;
        for ($i = 0, $i_max = count($list); $i < $i_max; ++$i) {
            if ($cur_len > self::MAX_PCRE_LENGTH) {
                // seems like the length of this pcre is growing exorbitantly
                $regexp_list[++$list_key] = $this->optimize_regexp_list_tokens_to_string($tokens);
                $num_subpatterns = substr_count($regexp_list[$list_key], '(?:');
                $tokens = [];
                $cur_len = 0;
            }
            $level = 0;
            $entry = preg_quote((string) $list[$i], $regexp_delimiter);
            $pointer = &$tokens;
            // properly assign the new entry to the correct position in the token array
            // possibly generate smaller common denominator keys
            while (true) {
                // get the common denominator
                if (isset($prev_keys[$level])) {
                    if ($prev_keys[$level] == $entry) {
                        // this is a duplicate entry, skip it
                        continue 2;
                    }
                    $char = 0;
                    while (isset($entry[$char]) && isset($prev_keys[$level][$char])
                           && $entry[$char] == $prev_keys[$level][$char]) {
                        ++$char;
                    }
                    if ($char > 0) {
                        // this entry has at least some chars in common with the current key
                        if ($char == strlen($prev_keys[$level])) {
                            // current key is totally matched, i.e. this entry has just some bits appended
                            $pointer = &$pointer[$prev_keys[$level]];
                        }
                        else {
                            // only part of the keys match
                            $new_key_part1 = substr($prev_keys[$level], 0, $char);
                            $new_key_part2 = substr($prev_keys[$level], $char);

                            if (in_array($new_key_part1[0], $regex_chars)
                                || in_array($new_key_part2[0], $regex_chars)
                            ) {
                                // this is bad, a regex char as first character
                                $pointer[$entry] = ['' => true];
                                array_splice($prev_keys, $level, count($prev_keys), $entry);
                                $cur_len += strlen($entry);
                                continue;
                            }
                            else {
                                // relocate previous tokens
                                $pointer[$new_key_part1] = [$new_key_part2 => $pointer[$prev_keys[$level]]];
                                unset($pointer[$prev_keys[$level]]);
                                $pointer = &$pointer[$new_key_part1];
                                // recreate key index
                                array_splice($prev_keys, $level, count($prev_keys), [$new_key_part1, $new_key_part2]);
                                $cur_len += strlen($new_key_part2);
                            }
                        }
                        ++$level;
                        $entry = substr($entry, $char);
                        continue;
                    }
                    // else: fall trough, i.e. no common denominator was found
                }
                if ($level == 0 && !empty($tokens)) {
                    // we can dump current tokens into the string and throw them away afterwards
                    $new_entry = $this->optimize_regexp_list_tokens_to_string($tokens);
                    $new_subpatterns = substr_count($new_entry, '(?:');
                    if (self::MAX_PCRE_SUBPATTERNS && $num_subpatterns + $new_subpatterns > self::MAX_PCRE_SUBPATTERNS) {
                        $regexp_list[++$list_key] = $new_entry;
                        $num_subpatterns = $new_subpatterns;
                    }
                    else {
                        if (!empty($regexp_list[$list_key])) {
                            $new_entry = '|' . $new_entry;
                        }
                        $regexp_list[$list_key] .= $new_entry;
                        $num_subpatterns += $new_subpatterns;
                    }
                    $tokens = [];
                    $cur_len = 0;
                }
                // no further common denominator found
                $pointer[$entry] = ['' => true];
                array_splice($prev_keys, $level, count($prev_keys), $entry);

                $cur_len += strlen($entry);
                break;
            }
            unset($list[$i]);
        }

        // make sure the last tokens get converted as well
        $new_entry = $this->optimize_regexp_list_tokens_to_string($tokens);
        if (self::MAX_PCRE_SUBPATTERNS && $num_subpatterns + substr_count($new_entry, '(?:') > self::MAX_PCRE_SUBPATTERNS) {
            if (!empty($regexp_list[$list_key])) {
                ++$list_key;
            }
            $regexp_list[$list_key] = $new_entry;
        }
        else {
            if (!empty($regexp_list[$list_key])) {
                $new_entry = '|' . $new_entry;
            }
            $regexp_list[$list_key] .= $new_entry;
        }

        return $regexp_list;
    }

    /**
     * this function creates the appropriate regexp string of an token array
     * you should not call this function directly, @see $this->optimize_regexp_list().
     *
     * @param &$tokens  array of tokens
     * @param $recursed bool to know wether we recursed or not
     *
     * @return string
     */
    private function optimize_regexp_list_tokens_to_string(&$tokens, $recursed = false)
    {
        $list = '';
        foreach ($tokens as $token => $sub_tokens) {
            $list .= $token;
            $close_entry = isset($sub_tokens['']);
            unset($sub_tokens['']);
            if (!empty($sub_tokens)) {
                $list .= '(?:' . $this->optimize_regexp_list_tokens_to_string($sub_tokens, true) . ')';
                if ($close_entry) {
                    // make sub_tokens optional
                    $list .= '?';
                }
            }
            $list .= '|';
        }
        if (!$recursed) {
            // do some optimizations
            // common trailing strings
            // BUGGY!
            //$list = preg_replace_callback('#(?<=^|\:|\|)\w+?(\w+)(?:\|.+\1)+(?=\|)#', create_function(
            //    '$matches', 'return "(?:" . preg_replace("#" . preg_quote($matches[1], "#") . "(?=\||$)#", "", $matches[0]) . ")" . $matches[1];'), $list);
            // (?:p)? => p?
            $list = preg_replace('#\(\?\:(.)\)\?#', '\1?', $list);
            // (?:a|b|c|d|...)? => [abcd...]?
            // TODO: a|bb|c => [ac]|bb
            static $callback_2;
            if (!isset($callback_2)) {
                $callback_2 = create_function('$matches', 'return "[" . str_replace("|", "", $matches[1]) . "]";');
            }
            $list = preg_replace_callback('#\(\?\:((?:.\|)+.)\)#', $callback_2, $list);
        }

        // return $list without trailing pipe
        return substr($list, 0, -1);
    }

    /**
     * Creates a new keyword group
     *
     * @param int     The key of the keyword group to create
     * @param string  The styles for the keyword group
     * @param boolean Whether the keyword group is case sensitive ornot
     * @param array   The words to use for the keyword group
     *
     * @return bool
     */
    function add_keyword_group($key, $styles, $case_sensitive = true, $words = [])
    {
        $words = (array) $words;
        if (empty($words)) {
            // empty word lists mess up highlighting
            return false;
        }

        //Add the new keyword group internally
        $this->language_data['KEYWORDS'][$key] = $words;
        $this->lexic_permissions['KEYWORDS'][$key] = true;
        $this->language_data['CASE_SENSITIVE'][$key] = $case_sensitive;
        $this->language_data['STYLES']['KEYWORDS'][$key] = $styles;

        // cache keyword regexp
        if ($this->parse_cache_built) {
            $this->optimize_keyword_group($key);
        }

        return true;
    }

    /**
     * Removes a keyword group
     *
     * @param int    The key of the keyword group to remove
     */
    function remove_keyword_group($key)
    {
        //Remove the keyword group internally
        unset($this->language_data['KEYWORDS'][$key]);
        unset($this->lexic_permissions['KEYWORDS'][$key]);
        unset($this->language_data['CASE_SENSITIVE'][$key]);
        unset($this->language_data['STYLES']['KEYWORDS'][$key]);
        unset($this->language_data['CACHED_KEYWORD_LISTS'][$key]);
    }

    /**
     * Sets the content of the header block
     *
     * @param string The content of the header block
     */
    function set_header_content($content)
    {
        $this->header_content = $content;
    }

    /**
     * Sets the content of the footer block
     *
     * @param string The content of the footer block
     */
    function set_footer_content($content)
    {
        $this->footer_content = $content;
    }

    /**
     * Sets the style for the header content
     *
     * @param string The style for the header content
     */
    function set_header_content_style($style)
    {
        $this->header_content_style = $style;
    }

    /**
     * Sets the style for the footer content
     *
     * @param string The style for the footer content
     */
    function set_footer_content_style($style)
    {
        $this->footer_content_style = $style;
    }

    /**
     * Sets whether to force a surrounding block around
     * the highlighted code or not
     *
     * @param boolean Tells whether to enable or disable this feature
     */
    function enable_inner_code_block($flag)
    {
        $this->force_code_block = (bool) $flag;
    }

    /**
     * Sets the base URL to be used for keywords
     *
     * @param int    The key of the keyword group to set the URL for
     * @param string The URL to set for the group. If {FNAME} is in
     *               the url somewhere, it is replaced by the keyword
     *               that the URL is being made for
     */
    function set_url_for_keyword_group($group, $url)
    {
        $this->language_data['URLS'][$group] = $url;
    }

    /**
     * Sets styles for links in code
     *
     * @param int    A constant that specifies what state the style is being
     *               set for - e.g. :hover or :visited
     * @param string The styles to use for that state
     */
    function set_link_styles($type, $styles)
    {
        $this->link_styles[$type] = $styles;
    }

    /**
     * Sets the target for links in code
     *
     * @param string The target for links in the code, e.g. _blank
     */
    function set_link_target($target)
    {
        if (!$target) {
            $this->link_target = '';
        }
        else {
            $this->link_target = ' target="' . $target . '"';
        }
    }

    /**
     * Sets styles for important parts of the code
     *
     * @param string The styles to use on important parts of the code
     */
    function set_important_styles($styles)
    {
        $this->important_styles = $styles;
    }

    /**
     * Whether CSS IDs should be added to each line
     *
     * @param boolean If true, IDs will be added to each line.
     */
    function enable_ids($flag = true)
    {
        $this->add_ids = ($flag) ? true : false;
    }

    /**
     * Specifies which lines to highlight extra
     *
     * The extra style parameter was added in 1.0.7.21.
     *
     * @param mixed  An array of line numbers to highlight, or just a line
     *               number on its own.
     * @param string A string specifying the style to use for this line.
     *               If null is specified, the default style is used.
     *               If false is specified, the line will be removed from
     *               special highlighting
     *
     * @todo  Some data replication here that could be cut down on
     */
    function highlight_lines_extra($lines, $style = null)
    {
        if (is_array($lines)) {
            //Split up the job using single lines at a time
            foreach ($lines as $line) {
                $this->highlight_lines_extra($line, $style);
            }
        }
        else {
            //Mark the line as being highlighted specially
            $lines = intval($lines);
            $this->highlight_extra_lines[$lines] = $lines;

            //Decide on which style to use
            if ($style === null) { //Check if we should use default style
                unset($this->highlight_extra_lines_styles[$lines]);
            }
            elseif ($style === false) { //Check if to remove this line
                unset($this->highlight_extra_lines[$lines]);
                unset($this->highlight_extra_lines_styles[$lines]);
            }
            else {
                $this->highlight_extra_lines_styles[$lines] = $style;
            }
        }
    }

    /**
     * Sets the style for extra-highlighted lines
     *
     * @param string The style for extra-highlighted lines
     */
    function set_highlight_lines_extra_style($styles)
    {
        $this->highlight_extra_lines_style = $styles;
    }

    /**
     * Sets the line-ending
     *
     * @param string The new line-ending
     */
    function set_line_ending($line_ending)
    {
        $this->line_ending = (string) $line_ending;
    }

    /**
     * Sets what number line numbers should start at. Should
     * be a positive integer, and will be converted to one.
     *
     * <b>Warning:</b> Using this method will add the "start"
     * attribute to the &lt;ol&gt; that is used for line numbering.
     * This is <b>not</b> valid XHTML strict, so if that's what you
     * care about then don't use this method. Firefox is getting
     * support for the CSS method of doing this in 1.1 and Opera
     * has support for the CSS method, but (of course) IE doesn't
     * so it's not worth doing it the CSS way yet.
     *
     * @param int The number to start line numbers at
     */
    function start_line_numbers_at($number)
    {
        $this->line_numbers_start = abs(intval($number));
    }

    /**
     * Sets the encoding used for htmlspecialchars(), for international
     * support.
     *
     * NOTE: This is not needed for now because htmlspecialchars() is not
     * being used (it has a security hole in PHP4 that has not been patched).
     * Maybe in a future version it may make a return for speed reasons, but
     * I doubt it.
     *
     * @param string The encoding to use for the source
     */
    function set_encoding($encoding)
    {
        if ($encoding) {
            $this->encoding = strtolower($encoding);
        }
    }

    /**
     * Turns linking of keywords on or off.
     *
     * @param boolean If true, links will be added to keywords
     */
    function enable_keyword_links($enable = true)
    {
        $this->keyword_links = (bool) $enable;
    }

    /**
     * Returns the code in $this->source, highlighted and surrounded by the
     * nessecary HTML.
     *
     * This should only be called ONCE, cos it's SLOW! If you want to highlight
     * the same source multiple times, you're better off doing a whole lot of
     * str_replaces to replace the &lt;span&gt;s
     */
    function parseCode()
    {
        // Start the timer
        $start_time = microtime();

        // Replace all newlines to a common form.
        $code = str_replace("\r\n", "\n", $this->source);
        $code = str_replace("\r", "\n", $code);

        // Firstly, if there is an error, we won't highlight
        if ($this->error) {
            //Escape the source for output
            $result = $this->hsc($this->source);

            //This fix is related to SF#1923020, but has to be applied regardless of
            //actually highlighting symbols.
            $result = str_replace(['<SEMI>', '<PIPE>'], [';', '|'], $result);

            // Timing is irrelevant
            $this->set_time($start_time, $start_time);
            $this->finalise($result);

            return $result;
        }

        // make sure the parse cache is up2date
        if (!$this->parse_cache_built) {
            $this->build_parse_cache();
        }

        // Initialise various stuff
        $length = strlen($code);
        $COMMENT_MATCHED = false;
        $stuff_to_parse = '';
        $endresult = '';

        if ($this->strict_mode) {
            // Break the source into bits. Each bit will be a portion of the code
            // within script delimiters - for example, HTML between < and >
            $k = 0;
            $parts = [];
            $matches = [];
            $next_match_pointer = null;
            // we use a copy to unset delimiters on demand (when they are not found)
            $delim_copy = $this->language_data['SCRIPT_DELIMITERS'];
            $i = 0;
            while ($i < $length) {
                $next_match_pos = $length + 1; // never true
                foreach ($delim_copy as $dk => $delimiters) {
                    if (is_array($delimiters)) {
                        foreach ($delimiters as $open => $close) {
                            // make sure the cache is setup properly
                            if (!isset($matches[$dk][$open])) {
                                $matches[$dk][$open] = [
                                    'next_match' => -1,
                                    'dk'         => $dk,

                                    'open'        => $open, // needed for grouping of adjacent code blocks (see below)
                                    'open_strlen' => strlen($open),

                                    'close'        => $close,
                                    'close_strlen' => strlen($close),
                                ];
                            }
                            // Get the next little bit for this opening string
                            if ($matches[$dk][$open]['next_match'] < $i) {
                                // only find the next pos if it was not already cached
                                $open_pos = strpos($code, $open, $i);
                                if ($open_pos === false) {
                                    // no match for this delimiter ever
                                    unset($delim_copy[$dk][$open]);
                                    continue;
                                }
                                $matches[$dk][$open]['next_match'] = $open_pos;
                            }
                            if ($matches[$dk][$open]['next_match'] < $next_match_pos) {
                                //So we got a new match, update the close_pos
                                $matches[$dk][$open]['close_pos'] =
                                    strpos($code, $close, $matches[$dk][$open]['next_match'] + 1);

                                $next_match_pointer =& $matches[$dk][$open];
                                $next_match_pos = $matches[$dk][$open]['next_match'];
                            }
                        }
                    }
                    else {
                        //So we should match an RegExp as Strict Block ...
                        /**
                         * The value in $delimiters is expected to be an RegExp
                         * containing exactly 2 matching groups:
                         *  - Group 1 is the opener
                         *  - Group 2 is the closer
                         */
                        if (preg_match($delimiters, $code, $matches_rx, PREG_OFFSET_CAPTURE, $i)) {
                            //We got a match ...
                            if (isset($matches_rx['start']) && isset($matches_rx['end'])) {
                                $matches[$dk] = [
                                    'next_match' => $matches_rx['start'][1],
                                    'dk'         => $dk,

                                    'close_strlen' => strlen($matches_rx['end'][0]),
                                    'close_pos'    => $matches_rx['end'][1],
                                ];
                            }
                            else {
                                $matches[$dk] = [
                                    'next_match' => $matches_rx[1][1],
                                    'dk'         => $dk,

                                    'close_strlen' => strlen($matches_rx[2][0]),
                                    'close_pos'    => $matches_rx[2][1],
                                ];
                            }
                        }
                        else {
                            // no match for this delimiter ever
                            unset($delim_copy[$dk]);
                            continue;
                        }

                        if ($matches[$dk]['next_match'] <= $next_match_pos) {
                            $next_match_pointer =& $matches[$dk];
                            $next_match_pos = $matches[$dk]['next_match'];
                        }
                    }
                }

                // non-highlightable text
                $parts[$k] = [
                    1 => substr($code, $i, $next_match_pos - $i),
                ];
                ++$k;

                if ($next_match_pos > $length) {
                    // out of bounds means no next match was found
                    break;
                }

                // highlightable code
                $parts[$k][0] = $next_match_pointer['dk'];

                //Only combine for non-rx script blocks
                if (is_array($delim_copy[$next_match_pointer['dk']])) {
                    // group adjacent script blocks, e.g. <foobar><asdf> should be one block, not three!
                    $i = $next_match_pos + $next_match_pointer['open_strlen'];
                    while (true) {
                        $close_pos = strpos($code, $next_match_pointer['close'], $i);
                        if ($close_pos == false) {
                            break;
                        }
                        $i = $close_pos + $next_match_pointer['close_strlen'];
                        if ($i == $length) {
                            break;
                        }
                        if ($code[$i] == $next_match_pointer['open'][0] && ($next_match_pointer['open_strlen'] == 1 ||
                                                                            substr($code, $i, $next_match_pointer['open_strlen']) == $next_match_pointer['open'])
                        ) {
                            // merge adjacent but make sure we don't merge things like <tag><!-- comment -->
                            foreach ($matches as $submatches) {
                                foreach ($submatches as $match) {
                                    if ($match['next_match'] == $i) {
                                        // a different block already matches here!
                                        break 3;
                                    }
                                }
                            }
                        }
                        else {
                            break;
                        }
                    }
                }
                else {
                    $close_pos = $next_match_pointer['close_pos'] + $next_match_pointer['close_strlen'];
                    $i = $close_pos;
                }

                if ($close_pos === false) {
                    // no closing delimiter found!
                    $parts[$k][1] = substr($code, $next_match_pos);
                    ++$k;
                    break;
                }
                else {
                    $parts[$k][1] = substr($code, $next_match_pos, $i - $next_match_pos);
                    ++$k;
                }
            }
            unset($delim_copy, $next_match_pointer, $next_match_pos, $matches);
            $num_parts = $k;

            if ($num_parts == 1 && $this->strict_mode == self::MAYBE) {
                // when we have only one part, we don't have anything to highlight at all.
                // if we have a "maybe" strict language, this should be handled as highlightable code
                $parts = [
                    0 => [
                        0 => '',
                        1 => '',
                    ],
                    1 => [
                        0 => null,
                        1 => $parts[0][1],
                    ],
                ];
                $num_parts = 2;
            }

        }
        else {
            // Not strict mode - simply dump the source into
            // the array at index 1 (the first highlightable block)
            $parts = [
                0 => [
                    0 => '',
                    1 => '',
                ],
                1 => [
                    0 => null,
                    1 => $code,
                ],
            ];
            $num_parts = 2;
        }

        //Unset variables we won't need any longer
        unset($code);

        //Preload some repeatedly used values regarding hardquotes ...
        $hq = isset($this->language_data['HARDQUOTE']) ? $this->language_data['HARDQUOTE'][0] : false;
        $hq_strlen = strlen($hq);

        //Preload if line numbers are to be generated afterwards
        //Added a check if line breaks should be forced even without line numbers, fixes SF#1727398
        $check_linenumbers = $this->line_numbers != self::OPT_LINE_NUMBERS_NONE ||
                             !empty($this->highlight_extra_lines) || !$this->allow_multiline_span;

        //preload the escape char for faster checking ...
        $escaped_escape_char = $this->hsc($this->language_data['ESCAPE_CHAR']);

        // this is used for single-line comments
        $sc_disallowed_before = "";
        $sc_disallowed_after = "";

        if (isset($this->language_data['PARSER_CONTROL'])) {
            if (isset($this->language_data['PARSER_CONTROL']['COMMENTS'])) {
                if (isset($this->language_data['PARSER_CONTROL']['COMMENTS']['DISALLOWED_BEFORE'])) {
                    $sc_disallowed_before = $this->language_data['PARSER_CONTROL']['COMMENTS']['DISALLOWED_BEFORE'];
                }
                if (isset($this->language_data['PARSER_CONTROL']['COMMENTS']['DISALLOWED_AFTER'])) {
                    $sc_disallowed_after = $this->language_data['PARSER_CONTROL']['COMMENTS']['DISALLOWED_AFTER'];
                }
            }
        }

        //Fix for SF#1932083: Multichar Quotemarks unsupported
        $is_string_starter = [];
        if ($this->lexic_permissions['STRINGS']) {
            foreach ($this->language_data['QUOTEMARKS'] as $quotemark) {
                if (!isset($is_string_starter[$quotemark[0]])) {
                    $is_string_starter[$quotemark[0]] = (string) $quotemark;
                }
                elseif (is_string($is_string_starter[$quotemark[0]])) {
                    $is_string_starter[$quotemark[0]] = [
                        $is_string_starter[$quotemark[0]],
                        $quotemark];
                }
                else {
                    $is_string_starter[$quotemark[0]][] = $quotemark;
                }
            }
        }

        // Now we go through each part. We know that even-indexed parts are
        // code that shouldn't be highlighted, and odd-indexed parts should
        // be highlighted
        for ($key = 0; $key < $num_parts; ++$key) {
            $STRICTATTRS = '';

            // If this block should be highlighted...
            if (!($key & 1)) {
                // Else not a block to highlight
                $endresult .= $this->hsc($parts[$key][1]);
                unset($parts[$key]);
                continue;
            }

            $result = '';
            $part = $parts[$key][1];

            $highlight_part = true;
            if ($this->strict_mode && !is_null($parts[$key][0])) {
                // get the class key for this block of code
                $script_key = $parts[$key][0];
                $highlight_part = $this->language_data['HIGHLIGHT_STRICT_BLOCK'][$script_key];
                if ($this->language_data['STYLES']['SCRIPT'][$script_key] != '' &&
                    $this->lexic_permissions['SCRIPT']
                ) {
                    // Add a span element around the source to
                    // highlight the overall source block
                    if (!$this->use_classes &&
                        $this->language_data['STYLES']['SCRIPT'][$script_key] != ''
                    ) {
                        $attributes = ' style="' . $this->language_data['STYLES']['SCRIPT'][$script_key] . '"';
                    }
                    else {
                        $attributes = ' class="sc' . $script_key . '"';
                    }
                    $result .= "<span$attributes>";
                    $STRICTATTRS = $attributes;
                }
            }

            if ($highlight_part) {
                // Now, highlight the code in this block. This code
                // is really the engine of GenSynth (along with the method
                // parse_non_string_part).

                // cache comment regexps incrementally
                $next_comment_regexp_key = '';
                $next_comment_regexp_pos = -1;
                $next_comment_multi_pos = -1;
                $next_comment_single_pos = -1;
                $comment_regexp_cache_per_key = [];
                $comment_multi_cache_per_key = [];
                $comment_single_cache_per_key = [];
                $next_open_comment_multi = '';
                $next_comment_single_key = '';
                $escape_regexp_cache_per_key = [];
                $next_escape_regexp_key = '';
                $next_escape_regexp_pos = -1;

                $length = strlen($part);
                for ($i = 0; $i < $length; ++$i) {
                    // Get the next char
                    $char = $part[$i];
                    $char_len = 1;

                    // update regexp comment cache if needed
                    if (isset($this->language_data['COMMENT_REGEXP']) && $next_comment_regexp_pos < $i) {
                        $next_comment_regexp_pos = $length;
                        foreach ($this->language_data['COMMENT_REGEXP'] as $comment_key => $regexp) {
                            $match_i = false;
                            if (isset($comment_regexp_cache_per_key[$comment_key]) &&
                                ($comment_regexp_cache_per_key[$comment_key]['pos'] >= $i ||
                                 $comment_regexp_cache_per_key[$comment_key]['pos'] === false)
                            ) {
                                // we have already matched something
                                if ($comment_regexp_cache_per_key[$comment_key]['pos'] === false) {
                                    // this comment is never matched
                                    continue;
                                }
                                $match_i = $comment_regexp_cache_per_key[$comment_key]['pos'];
                            }
                            elseif (preg_match($regexp, $part, $match, PREG_OFFSET_CAPTURE, $i)) {
                                $match_i = $match[0][1];

                                $comment_regexp_cache_per_key[$comment_key] = [
                                    'key'    => $comment_key,
                                    'length' => strlen($match[0][0]),
                                    'pos'    => $match_i,
                                ];
                            }
                            else {
                                $comment_regexp_cache_per_key[$comment_key]['pos'] = false;
                                continue;
                            }

                            if ($match_i !== false && $match_i < $next_comment_regexp_pos) {
                                $next_comment_regexp_pos = $match_i;
                                $next_comment_regexp_key = $comment_key;
                                if ($match_i === $i) {
                                    break;
                                }
                            }
                        }
                    }

                    $string_started = false;

                    if (isset($is_string_starter[$char])) {
                        // Possibly the start of a new string ...

                        //Check which starter it was ...
                        //Fix for SF#1932083: Multichar Quotemarks unsupported
                        if (is_array($is_string_starter[$char])) {
                            $char_new = '';
                            foreach ($is_string_starter[$char] as $testchar) {
                                if ($testchar === substr($part, $i, strlen($testchar)) &&
                                    strlen($testchar) > strlen($char_new)
                                ) {
                                    $char_new = $testchar;
                                    $string_started = true;
                                }
                            }
                            if ($string_started) {
                                $char = $char_new;
                            }
                        }
                        else {
                            $testchar = $is_string_starter[$char];
                            if ($testchar === substr($part, $i, strlen($testchar))) {
                                $char = $testchar;
                                $string_started = true;
                            }
                        }
                        $char_len = strlen($char);
                    }

                    if ($string_started && ($i != $next_comment_regexp_pos)) {
                        // Hand out the correct style information for this string
                        $string_key = array_search($char, $this->language_data['QUOTEMARKS']);
                        if (!isset($this->language_data['STYLES']['STRINGS'][$string_key]) ||
                            !isset($this->language_data['STYLES']['ESCAPE_CHAR'][$string_key])
                        ) {
                            $string_key = 0;
                        }

                        // parse the stuff before this
                        $result .= $this->parse_non_string_part($stuff_to_parse);
                        $stuff_to_parse = '';

                        if (!$this->use_classes) {
                            $string_attributes = ' style="' . $this->language_data['STYLES']['STRINGS'][$string_key] . '"';
                        }
                        else {
                            $string_attributes = ' class="st' . $string_key . '"';
                        }

                        // now handle the string
                        $string = "<span$string_attributes>" . self::hsc($char);
                        $start = $i + $char_len;
                        $string_open = true;

                        if (empty($this->language_data['ESCAPE_REGEXP'])) {
                            $next_escape_regexp_pos = $length;
                        }

                        do {
                            //Get the regular ending pos ...
                            $close_pos = strpos($part, $char, $start);
                            if (false === $close_pos) {
                                $close_pos = $length;
                            }

                            if ($this->lexic_permissions['ESCAPE_CHAR']) {
                                // update escape regexp cache if needed
                                if (isset($this->language_data['ESCAPE_REGEXP']) && $next_escape_regexp_pos < $start) {
                                    $next_escape_regexp_pos = $length;
                                    foreach ($this->language_data['ESCAPE_REGEXP'] as $escape_key => $regexp) {
                                        $match_i = false;
                                        if (isset($escape_regexp_cache_per_key[$escape_key]) &&
                                            ($escape_regexp_cache_per_key[$escape_key]['pos'] >= $start ||
                                             $escape_regexp_cache_per_key[$escape_key]['pos'] === false)
                                        ) {
                                            // we have already matched something
                                            if ($escape_regexp_cache_per_key[$escape_key]['pos'] === false) {
                                                // this comment is never matched
                                                continue;
                                            }
                                            $match_i = $escape_regexp_cache_per_key[$escape_key]['pos'];
                                        }
                                        elseif (preg_match($regexp, $part, $match, PREG_OFFSET_CAPTURE, $start)) {
                                            $match_i = $match[0][1];

                                            $escape_regexp_cache_per_key[$escape_key] = [
                                                'key'    => $escape_key,
                                                'length' => strlen($match[0][0]),
                                                'pos'    => $match_i,
                                            ];
                                        }
                                        else {
                                            $escape_regexp_cache_per_key[$escape_key]['pos'] = false;
                                            continue;
                                        }

                                        if ($match_i !== false && $match_i < $next_escape_regexp_pos) {
                                            $next_escape_regexp_pos = $match_i;
                                            $next_escape_regexp_key = $escape_key;
                                            if ($match_i === $start) {
                                                break;
                                            }
                                        }
                                    }
                                }

                                //Find the next simple escape position
                                if ('' != $this->language_data['ESCAPE_CHAR']) {
                                    $simple_escape = strpos($part, $this->language_data['ESCAPE_CHAR'], $start);
                                    if (false === $simple_escape) {
                                        $simple_escape = $length;
                                    }
                                }
                                else {
                                    $simple_escape = $length;
                                }
                            }
                            else {
                                $next_escape_regexp_pos = $length;
                                $simple_escape = $length;
                            }

                            if ($simple_escape < $next_escape_regexp_pos &&
                                $simple_escape < $length &&
                                $simple_escape < $close_pos
                            ) {
                                //The nexxt escape sequence is a simple one ...
                                $es_pos = $simple_escape;

                                //Add the stuff not in the string yet ...
                                $string .= $this->hsc(substr($part, $start, $es_pos - $start));

                                //Get the style for this escaped char ...
                                if (!$this->use_classes) {
                                    $escape_char_attributes = ' style="' . $this->language_data['STYLES']['ESCAPE_CHAR'][0] . '"';
                                }
                                else {
                                    $escape_char_attributes = ' class="es0"';
                                }

                                //Add the style for the escape char ...
                                $string .= "<span$escape_char_attributes>" .
                                           self::hsc($this->language_data['ESCAPE_CHAR']);

                                //Get the byte AFTER the ESCAPE_CHAR we just found
                                $es_char = $part[$es_pos + 1];
                                if ($es_char == "\n") {
                                    // don't put a newline around newlines
                                    $string .= "</span>\n";
                                    $start = $es_pos + 2;
                                }
                                elseif (ord($es_char) >= 128) {
                                    //This is an non-ASCII char (UTF8 or single byte)
                                    //This code tries to work around SF#2037598 ...
                                    if (function_exists('mb_substr')) {
                                        $es_char_m = mb_substr(substr($part, $es_pos + 1, 16), 0, 1, $this->encoding);
                                        $string .= $es_char_m . '</span>';
                                    }
                                    elseif ('utf-8' == $this->encoding) {
                                        if (preg_match("/[\xC2-\xDF][\x80-\xBF]" .
                                                       "|\xE0[\xA0-\xBF][\x80-\xBF]" .
                                                       "|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}" .
                                                       "|\xED[\x80-\x9F][\x80-\xBF]" .
                                                       "|\xF0[\x90-\xBF][\x80-\xBF]{2}" .
                                                       "|[\xF1-\xF3][\x80-\xBF]{3}" .
                                                       "|\xF4[\x80-\x8F][\x80-\xBF]{2}/s",
                                                       $part, $es_char_m, null, $es_pos + 1)) {
                                            $es_char_m = $es_char_m[0];
                                        }
                                        else {
                                            $es_char_m = $es_char;
                                        }
                                        $string .= $this->hsc($es_char_m) . '</span>';
                                    }
                                    else {
                                        $es_char_m = $this->hsc($es_char);
                                    }
                                    $start = $es_pos + strlen($es_char_m) + 1;
                                }
                                else {
                                    $string .= $this->hsc($es_char) . '</span>';
                                    $start = $es_pos + 2;
                                }
                            }
                            elseif ($next_escape_regexp_pos < $length &&
                                    $next_escape_regexp_pos < $close_pos
                            ) {
                                $es_pos = $next_escape_regexp_pos;
                                //Add the stuff not in the string yet ...
                                $string .= $this->hsc(substr($part, $start, $es_pos - $start));

                                //Get the key and length of this match ...
                                $escape = $escape_regexp_cache_per_key[$next_escape_regexp_key];
                                $escape_str = substr($part, $es_pos, $escape['length']);
                                $escape_key = $escape['key'];

                                //Get the style for this escaped char ...
                                if (!$this->use_classes) {
                                    $escape_char_attributes = ' style="' . $this->language_data['STYLES']['ESCAPE_CHAR'][$escape_key] . '"';
                                }
                                else {
                                    $escape_char_attributes = ' class="es' . $escape_key . '"';
                                }

                                //Add the style for the escape char ...
                                $string .= "<span$escape_char_attributes>" .
                                           $this->hsc($escape_str) . '</span>';

                                $start = $es_pos + $escape['length'];
                            }
                            else {
                                //Copy the remainder of the string ...
                                $string .= $this->hsc(substr($part, $start, $close_pos - $start + $char_len)) . '</span>';
                                $start = $close_pos + $char_len;
                                $string_open = false;
                            }
                        } while ($string_open);

                        if ($check_linenumbers) {
                            // Are line numbers used? If, we should end the string before
                            // the newline and begin it again (so when <li>s are put in the source
                            // remains XHTML compliant)
                            // note to self: This opens up possibility of config files specifying
                            // that languages can/cannot have multiline strings???
                            $string = str_replace("\n", "</span>\n<span$string_attributes>", $string);
                        }

                        $result .= $string;
                        $string = '';
                        $i = $start - 1;
                        continue;
                    }
                    elseif ($this->lexic_permissions['STRINGS'] && $hq && $hq[0] == $char &&
                            substr($part, $i, $hq_strlen) == $hq && ($i != $next_comment_regexp_pos)
                    ) {
                        // The start of a hard quoted string
                        if (!$this->use_classes) {
                            $string_attributes = ' style="' . $this->language_data['STYLES']['STRINGS']['HARD'] . '"';
                            $escape_char_attributes = ' style="' . $this->language_data['STYLES']['ESCAPE_CHAR']['HARD'] . '"';
                        }
                        else {
                            $string_attributes = ' class="st_h"';
                            $escape_char_attributes = ' class="es_h"';
                        }
                        // parse the stuff before this
                        $result .= $this->parse_non_string_part($stuff_to_parse);
                        $stuff_to_parse = '';

                        // now handle the string
                        $string = '';

                        // look for closing quote
                        $start = $i + $hq_strlen;
                        while ($close_pos = strpos($part, $this->language_data['HARDQUOTE'][1], $start)) {
                            $start = $close_pos + 1;
                            if ($this->lexic_permissions['ESCAPE_CHAR'] && $part[$close_pos - 1] == $this->language_data['HARDCHAR'] &&
                                (($i + $hq_strlen) != ($close_pos))
                            ) { //Support empty string for HQ escapes if Starter = Escape
                                // make sure this quote is not escaped
                                foreach ($this->language_data['HARDESCAPE'] as $hardescape) {
                                    if (substr($part, $close_pos - 1, strlen($hardescape)) == $hardescape) {
                                        // check wether this quote is escaped or if it is something like '\\'
                                        $escape_char_pos = $close_pos - 1;
                                        while ($escape_char_pos > 0
                                               && $part[$escape_char_pos - 1] == $this->language_data['HARDCHAR']) {
                                            --$escape_char_pos;
                                        }
                                        if (($close_pos - $escape_char_pos) & 1) {
                                            // uneven number of escape chars => this quote is escaped
                                            continue 2;
                                        }
                                    }
                                }
                            }

                            // found closing quote
                            break;
                        }

                        //Found the closing delimiter?
                        if (!$close_pos) {
                            // span till the end of this $part when no closing delimiter is found
                            $close_pos = $length;
                        }

                        //Get the actual string
                        $string = substr($part, $i, $close_pos - $i + 1);
                        $i = $close_pos;

                        // handle escape chars and encode html chars
                        // (special because when we have escape chars within our string they may not be escaped)
                        if ($this->lexic_permissions['ESCAPE_CHAR'] && $this->language_data['ESCAPE_CHAR']) {
                            $start = 0;
                            $new_string = '';
                            while ($es_pos = strpos($string, $this->language_data['ESCAPE_CHAR'], $start)) {
                                // hmtl escape stuff before
                                $new_string .= $this->hsc(substr($string, $start, $es_pos - $start));
                                // check if this is a hard escape
                                foreach ($this->language_data['HARDESCAPE'] as $hardescape) {
                                    if (substr($string, $es_pos, strlen($hardescape)) == $hardescape) {
                                        // indeed, this is a hardescape
                                        $new_string .= "<span$escape_char_attributes>" .
                                                       $this->hsc($hardescape) . '</span>';
                                        $start = $es_pos + strlen($hardescape);
                                        continue 2;
                                    }
                                }
                                // not a hard escape, but a normal escape
                                // they come in pairs of two
                                $c = 0;
                                while (isset($string[$es_pos + $c]) && isset($string[$es_pos + $c + 1])
                                       && $string[$es_pos + $c] == $this->language_data['ESCAPE_CHAR']
                                       && $string[$es_pos + $c + 1] == $this->language_data['ESCAPE_CHAR']) {
                                    $c += 2;
                                }
                                if ($c) {
                                    $new_string .= "<span$escape_char_attributes>" .
                                                   str_repeat($escaped_escape_char, $c) .
                                                   '</span>';
                                    $start = $es_pos + $c;
                                }
                                else {
                                    // this is just a single lonely escape char...
                                    $new_string .= $escaped_escape_char;
                                    $start = $es_pos + 1;
                                }
                            }
                            $string = $new_string . $this->hsc(substr($string, $start));
                        }
                        else {
                            $string = $this->hsc($string);
                        }

                        if ($check_linenumbers) {
                            // Are line numbers used? If, we should end the string before
                            // the newline and begin it again (so when <li>s are put in the source
                            // remains XHTML compliant)
                            // note to self: This opens up possibility of config files specifying
                            // that languages can/cannot have multiline strings???
                            $string = str_replace("\n", "</span>\n<span$string_attributes>", $string);
                        }

                        $result .= "<span$string_attributes>" . $string . '</span>';
                        $string = '';
                        continue;
                    }
                    else {
                        //Have a look for regexp comments
                        if ($i == $next_comment_regexp_pos) {
                            $COMMENT_MATCHED = true;
                            $comment = $comment_regexp_cache_per_key[$next_comment_regexp_key];
                            $test_str = $this->hsc(substr($part, $i, $comment['length']));
                            if ($this->lexic_permissions['COMMENTS']['MULTI']) {
                                if (!$this->use_classes) {
                                    $attributes = ' style="' . $this->language_data['STYLES']['COMMENTS'][$comment['key']] . '"';
                                }
                                else {
                                    $attributes = ' class="co' . $comment['key'] . '"';
                                }

                                $test_str = "<span$attributes>" . $test_str . "</span>";

                                // Short-cut through all the multiline code
                                if ($check_linenumbers) {
                                    // strreplace to put close span and open span around multiline newlines
                                    $test_str = str_replace(
                                        "\n", "</span>\n<span$attributes>",
                                        str_replace("\n ", "\n&nbsp;", $test_str)
                                    );
                                }
                            }

                            $i += $comment['length'] - 1;

                            // parse the rest
                            $result .= $this->parse_non_string_part($stuff_to_parse);
                            $stuff_to_parse = '';
                        }

                        // If we haven't matched a regexp comment, try multi-line comments
                        if (!$COMMENT_MATCHED) {
                            // Is this a multiline comment?
                            if (!empty($this->language_data['COMMENT_MULTI']) && $next_comment_multi_pos < $i) {
                                $next_comment_multi_pos = $length;
                                foreach ($this->language_data['COMMENT_MULTI'] as $open => $close) {
                                    $match_i = false;
                                    if (isset($comment_multi_cache_per_key[$open]) &&
                                        ($comment_multi_cache_per_key[$open] >= $i ||
                                         $comment_multi_cache_per_key[$open] === false)
                                    ) {
                                        // we have already matched something
                                        if ($comment_multi_cache_per_key[$open] === false) {
                                            // this comment is never matched
                                            continue;
                                        }
                                        $match_i = $comment_multi_cache_per_key[$open];
                                    }
                                    elseif (($match_i = stripos($part, $open, $i)) !== false) {
                                        $comment_multi_cache_per_key[$open] = $match_i;
                                    }
                                    else {
                                        $comment_multi_cache_per_key[$open] = false;
                                        continue;
                                    }
                                    if ($match_i !== false && $match_i < $next_comment_multi_pos) {
                                        $next_comment_multi_pos = $match_i;
                                        $next_open_comment_multi = $open;
                                        if ($match_i === $i) {
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($i == $next_comment_multi_pos) {
                                $open = $next_open_comment_multi;
                                $close = $this->language_data['COMMENT_MULTI'][$open];
                                $open_strlen = strlen($open);
                                $close_strlen = strlen($close);
                                $COMMENT_MATCHED = true;
                                $test_str_match = $open;
                                if ($this->lexic_permissions['COMMENTS']['MULTI']) {
                                    if (!$this->use_classes) {
                                        $attributes = ' style="' . $this->language_data['STYLES']['COMMENTS']['MULTI'] . '"';
                                    }
                                    else {
                                        $attributes = ' class="coMULTI"';
                                    }
                                    $test_str = "<span$attributes>" . $this->hsc($open);
                                }
                                else {
                                    $test_str = $this->hsc($open);
                                }

                                $close_pos = strpos($part, $close, $i + $open_strlen);

                                if ($close_pos === false) {
                                    $close_pos = $length;
                                }

                                // Short-cut through all the multiline code
                                $rest_of_comment = $this->hsc(substr($part, $i + $open_strlen, $close_pos - $i - $open_strlen + $close_strlen));
                                if ($this->lexic_permissions['COMMENTS']['MULTI'] && $check_linenumbers) {
                                    // strreplace to put close span and open span around multiline newlines
                                    $test_str .= str_replace(
                                        "\n", "</span>\n<span$attributes>",
                                        str_replace("\n ", "\n&nbsp;", $rest_of_comment)
                                    );
                                }
                                else {
                                    $test_str .= $rest_of_comment;
                                }

                                if ($this->lexic_permissions['COMMENTS']['MULTI']) {
                                    $test_str .= '</span>';
                                }

                                $i = $close_pos + $close_strlen - 1;

                                // parse the rest
                                $result .= $this->parse_non_string_part($stuff_to_parse);
                                $stuff_to_parse = '';
                            }
                        }

                        // If we haven't matched a multiline comment, try single-line comments
                        if (!$COMMENT_MATCHED) {
                            // cache potential single line comment occurances
                            if (!empty($this->language_data['COMMENT_SINGLE']) && $next_comment_single_pos < $i) {
                                $next_comment_single_pos = $length;
                                foreach ($this->language_data['COMMENT_SINGLE'] as $comment_key => $comment_mark) {
                                    $match_i = false;
                                    if (isset($comment_single_cache_per_key[$comment_key]) &&
                                        ($comment_single_cache_per_key[$comment_key] >= $i ||
                                         $comment_single_cache_per_key[$comment_key] === false)
                                    ) {
                                        // we have already matched something
                                        if ($comment_single_cache_per_key[$comment_key] === false) {
                                            // this comment is never matched
                                            continue;
                                        }
                                        $match_i = $comment_single_cache_per_key[$comment_key];
                                    }
                                    elseif (
                                        // case sensitive comments
                                        ($this->language_data['CASE_SENSITIVE'][self::COMMENTS] &&
                                         ($match_i = stripos($part, $comment_mark, $i)) !== false) ||
                                        // non case sensitive
                                        (!$this->language_data['CASE_SENSITIVE'][self::COMMENTS] &&
                                         (($match_i = strpos($part, $comment_mark, $i)) !== false))
                                    ) {
                                        $comment_single_cache_per_key[$comment_key] = $match_i;
                                    }
                                    else {
                                        $comment_single_cache_per_key[$comment_key] = false;
                                        continue;
                                    }
                                    if ($match_i !== false && $match_i < $next_comment_single_pos) {
                                        $next_comment_single_pos = $match_i;
                                        $next_comment_single_key = $comment_key;
                                        if ($match_i === $i) {
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($next_comment_single_pos == $i) {
                                $comment_key = $next_comment_single_key;
                                $comment_mark = $this->language_data['COMMENT_SINGLE'][$comment_key];
                                $com_len = strlen($comment_mark);

                                // This check will find special variables like $# in bash
                                // or compiler directives of Delphi beginning {$
                                if ((empty($sc_disallowed_before) || ($i == 0) ||
                                     (false === strpos($sc_disallowed_before, $part[$i - 1]))) &&
                                    (empty($sc_disallowed_after) || ($length <= $i + $com_len) ||
                                     (false === strpos($sc_disallowed_after, $part[$i + $com_len])))
                                ) {
                                    // this is a valid comment
                                    $COMMENT_MATCHED = true;
                                    if ($this->lexic_permissions['COMMENTS'][$comment_key]) {
                                        if (!$this->use_classes) {
                                            $attributes = ' style="' . $this->language_data['STYLES']['COMMENTS'][$comment_key] . '"';
                                        }
                                        else {
                                            $attributes = ' class="co' . $comment_key . '"';
                                        }
                                        $test_str = "<span$attributes>" . $this->hsc($this->change_case($comment_mark));
                                    }
                                    else {
                                        $test_str = $this->hsc($comment_mark);
                                    }

                                    //Check if this comment is the last in the source
                                    $close_pos = strpos($part, "\n", $i);
                                    $oops = false;
                                    if ($close_pos === false) {
                                        $close_pos = $length;
                                        $oops = true;
                                    }
                                    $test_str .= $this->hsc(substr($part, $i + $com_len, $close_pos - $i - $com_len));
                                    if ($this->lexic_permissions['COMMENTS'][$comment_key]) {
                                        $test_str .= "</span>";
                                    }

                                    // Take into account that the comment might be the last in the source
                                    if (!$oops) {
                                        $test_str .= "\n";
                                    }

                                    $i = $close_pos;

                                    // parse the rest
                                    $result .= $this->parse_non_string_part($stuff_to_parse);
                                    $stuff_to_parse = '';
                                }
                            }
                        }
                    }

                    // Where are we adding this char?
                    if (!$COMMENT_MATCHED) {
                        $stuff_to_parse .= $char;
                    }
                    else {
                        $result .= $test_str;
                        unset($test_str);
                        $COMMENT_MATCHED = false;
                    }
                }
                // Parse the last bit
                $result .= $this->parse_non_string_part($stuff_to_parse);
                $stuff_to_parse = '';
            }
            else {
                $result .= $this->hsc($part);
            }
            // Close the <span> that surrounds the block
            if ($STRICTATTRS != '') {
                $result = str_replace("\n", "</span>\n<span$STRICTATTRS>", $result);
                $result .= '</span>';
            }

            $endresult .= $result;
            unset($part, $parts[$key], $result);
        }

        //This fix is related to SF#1923020, but has to be applied regardless of
        //actually highlighting symbols.
        /** NOTE: memorypeak #3 */
        $endresult = str_replace(['<SEMI>', '<PIPE>'], [';', '|'], $endresult);

        //        // Parse the last stuff (redundant?)
        //        $result .= $this->parse_non_string_part($stuff_to_parse);

        // Lop off the very first and last spaces
        //        $result = substr($result, 1, -1);

        // We're finished: stop timing
        $this->set_time($start_time, microtime());

        $this->finalise($endresult);

        return $endresult;
    }

    /**
     * Secure replacement for PHP built-in function htmlspecialchars().
     *
     * See ticket #427 (http://wush.net/trac/wikka/ticket/427) for the rationale
     * for this replacement function.
     *
     * The INTERFACE for this function is almost the same as that for
     * htmlspecialchars(), with the same default for quote style; however, there
     * is no 'charset' parameter. The reason for this is as follows:
     *
     * The PHP docs say:
     *      "The third argument charset defines character set used in conversion."
     *
     * I suspect PHP's htmlspecialchars() is working at the byte-value level and
     * thus _needs_ to know (or asssume) a character set because the special
     * characters to be replaced could exist at different code points in
     * different character sets. (If indeed htmlspecialchars() works at
     * byte-value level that goes some  way towards explaining why the
     * vulnerability would exist in this function, too, and not only in
     * htmlentities() which certainly is working at byte-value level.)
     *
     * This replacement function however works at character level and should
     * therefore be "immune" to character set differences - so no charset
     * parameter is needed or provided. If a third parameter is passed, it will
     * be silently ignored.
     *
     * In the OUTPUT there is a minor difference in that we use '&#39;' instead
     * of PHP's '&#039;' for a single quote: this provides compatibility with
     *      get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES)
     * (see comment by mikiwoz at yahoo dot co dot uk on
     * http://php.net/htmlspecialchars); it also matches the entity definition
     * for XML 1.0
     * (http://www.w3.org/TR/xhtml1/dtds.html#a_dtd_Special_characters).
     * Like PHP we use a numeric character reference instead of '&apos;' for the
     * single quote. For the other special characters we use the named entity
     * references, as PHP is doing.
     *
     * @author      {@link http://wikkawiki.org/JavaWoman Marjolein Katsma}
     *
     * @license     http://www.gnu.org/copyleft/lgpl.html
     *              GNU Lesser General Public License
     * @copyright   Copyright 2007, {@link http://wikkawiki.org/CreditsPage
     *              Wikka Development Team}
     *
     * @param       string  $string string to be converted
     * @param       integer $quote_style
     *                              - ENT_COMPAT:   escapes &, <, > and double quote (default)
     *                              - ENT_NOQUOTES: escapes only &, < and >
     *                              - ENT_QUOTES:   escapes &, <, >, double and single quotes
     *
     * @return      string  converted string
     */
    private function hsc($string, $quote_style = ENT_COMPAT)
    {
        // init
        static $aTransSpecchar = [
            '&' => '&amp;',
            '"' => '&quot;',
            '<' => '&lt;',
            '>' => '&gt;',

            //This fix is related to SF#1923020, but has to be applied
            //regardless of actually highlighting symbols.

            //Circumvent a bug with symbol highlighting
            //This is required as ; would produce undesirable side-effects if it
            //was not to be processed as an entity.
            ';' => '<SEMI>', // Force ; to be processed as entity
            '|' => '<PIPE>' // Force | to be processed as entity
        ];                      // ENT_COMPAT set

        switch ($quote_style) {
            case ENT_NOQUOTES: // don't convert double quotes
                unset($aTransSpecchar['"']);
                break;
            case ENT_QUOTES: // convert single quotes as well
                $aTransSpecchar["'"] = '&#39;'; // (apos) htmlspecialchars() uses '&#039;'
                break;
        }

        // return translated string
        $r = strtr($string, $aTransSpecchar);

        if ($quote_style == ENT_NOQUOTES) {
            $aTransSpecchar['"'] = '&quot;';
        }

        return $r;
    }

    /**
     * Sets the time taken to parse the code
     *
     * @param string $start_time The microtime when parsing started
     * @param string $end_time   The microtime when parsing ended
     */
    private function set_time($start_time, $end_time)
    {
        $start = explode(' ', $start_time);
        $end = explode(' ', $end_time);
        $this->time = $end[0] + $end[1] - $start[0] - $start[1];
    }

    /**
     * Takes the parsed code and various options, and creates the HTML
     * surrounding it to make it look nice.
     *
     * @param  string $parsed_code The code already parsed (reference!)
     */
    private function finalise(&$parsed_code)
    {
        // Add HTML whitespace stuff if we're using the <div> header
        if ($this->header_type != self::OPT_HEADER_PRE && $this->header_type != self::OPT_HEADER_PRE_VALID) {
            $this->indent($parsed_code);
        }

        // purge some unnecessary stuff
        /** NOTE: memorypeak #1 */
        $parsed_code = preg_replace('#<span[^>]+>(\s*)</span>#', '\\1', $parsed_code);

        // If we are using IDs for line numbers, there needs to be an overall
        // ID set to prevent collisions.
        if ($this->add_ids && !$this->overall_id) {
            $this->overall_id = 'gensynth-' . substr(md5(microtime()), 0, 4);
        }

        // Get code into lines
        /** NOTE: memorypeak #2 */
        $code = explode("\n", $parsed_code);
        $parsed_code = $this->header();

        // If we're using line numbers, we insert <li>s and appropriate
        // markup to style them (otherwise we don't need to do anything)
        if ($this->line_numbers != self::OPT_LINE_NUMBERS_NONE && $this->header_type != self::OPT_HEADER_PRE_TABLE) {
            // If we're using the <pre> header, we shouldn't add newlines because
            // the <pre> will line-break them (and the <li>s already do this for us)
            $ls = ($this->header_type != self::OPT_HEADER_PRE && $this->header_type != self::OPT_HEADER_PRE_VALID) ? "\n" : '';

            // Set vars to defaults for following loop
            $i = 0;

            // Foreach line...
            for ($i = 0, $n = count($code); $i < $n;) {
                //Reset the attributes for a new line ...
                $attrs = [];

                // Make lines have at least one space in them if they're empty
                // BenBE: Checking emptiness using trim instead of relying on blanks
                if ('' == trim($code[$i])) {
                    $code[$i] = '&nbsp;';
                }

                // If this is a "special line"...
                if ($this->line_numbers == self::OPT_LINE_NUMBERS_FANCY &&
                    $i % $this->line_nth_row == ($this->line_nth_row - 1)
                ) {
                    // Set the attributes to style the line
                    if ($this->use_classes) {
                        //$attr = ' class="li2"';
                        $attrs['class'][] = 'li2';
                        $def_attr = ' class="de2"';
                    }
                    else {
                        //$attr = ' style="' . $this->line_style2 . '"';
                        $attrs['style'][] = $this->line_style2;
                        // This style "covers up" the special styles set for special lines
                        // so that styles applied to special lines don't apply to the actual
                        // code on that line
                        $def_attr = ' style="' . $this->code_style . '"';
                    }
                }
                else {
                    if ($this->use_classes) {
                        //$attr = ' class="li1"';
                        $attrs['class'][] = 'li1';
                        $def_attr = ' class="de1"';
                    }
                    else {
                        //$attr = ' style="' . $this->line_style1 . '"';
                        $attrs['style'][] = $this->line_style1;
                        $def_attr = ' style="' . $this->code_style . '"';
                    }
                }

                //Check which type of tag to insert for this line
                if ($this->header_type == self::OPT_HEADER_PRE_VALID) {
                    $start = "<pre$def_attr>";
                    $end = '</pre>';
                }
                else {
                    // Span or div?
                    $start = "<div$def_attr>";
                    $end = '</div>';
                }

                ++$i;

                // Are we supposed to use ids? If so, add them
                if ($this->add_ids) {
                    $attrs['id'][] = "$this->overall_id-$i";
                }

                //Is this some line with extra styles???
                if (in_array($i, $this->highlight_extra_lines)) {
                    if ($this->use_classes) {
                        if (isset($this->highlight_extra_lines_styles[$i])) {
                            $attrs['class'][] = "lx$i";
                        }
                        else {
                            $attrs['class'][] = "ln-xtra";
                        }
                    }
                    else {
                        array_push($attrs['style'], $this->get_line_style($i));
                    }
                }

                // Add in the line surrounded by appropriate list HTML
                $attr_string = '';
                foreach ($attrs as $key => $attr) {
                    $attr_string .= ' ' . $key . '="' . implode(' ', $attr) . '"';
                }

                $parsed_code .= "<li$attr_string>$start{$code[$i-1]}$end</li>$ls";
                unset($code[$i - 1]);
            }
        }
        else {
            $n = count($code);
            if ($this->use_classes) {
                $attributes = ' class="de1"';
            }
            else {
                $attributes = ' style="' . $this->code_style . '"';
            }
            if ($this->header_type == self::OPT_HEADER_PRE_VALID) {
                $parsed_code .= '<pre' . $attributes . '>';
            }
            elseif ($this->header_type == self::OPT_HEADER_PRE_TABLE) {
                if ($this->line_numbers != self::OPT_LINE_NUMBERS_NONE) {
                    if ($this->use_classes) {
                        $attrs = ' class="ln"';
                    }
                    else {
                        $attrs = ' style="' . $this->table_linenumber_style . '"';
                    }
                    $parsed_code .= '<td' . $attrs . '><pre' . $attributes . '>';
                    // get linenumbers
                    // we don't merge it with the for below, since it should be better for
                    // memory consumption this way
                    // @todo: but... actually it would still be somewhat nice to merge the two loops
                    //        the mem peaks are at different positions
                    for ($i = 0; $i < $n; ++$i) {
                        $close = 0;
                        $parsed_code .= $this->finalizeFancyLines($i, $close);

                        $parsed_code .= $this->line_numbers_start + $i;
                        if ($close) {
                            $parsed_code .= str_repeat('</span>', $close);
                        }
                        elseif ($i != $n) {
                            $parsed_code .= "\n";
                        }
                    }
                    $parsed_code .= '</pre></td><td' . $attributes . '>';
                }
                $parsed_code .= '<pre' . $attributes . '>';
            }
            // No line numbers, but still need to handle highlighting lines extra.
            // Have to use divs so the full width of the code is highlighted
            for ($i = 0; $i < $n; ++$i) {
                // Make lines have at least one space in them if they're empty
                // BenBE: Checking emptiness using trim instead of relying on blanks
                if ('' == trim($code[$i])) {
                    $code[$i] = '&nbsp;';
                }
                $close = 0;
                $parsed_code .= $this->finalizeFancyLines($i, $close);

                $parsed_code .= $code[$i];

                if ($close) {
                    $parsed_code .= str_repeat('</span>', $close);
                }
                elseif ($i + 1 < $n) {
                    $parsed_code .= "\n";
                }
                unset($code[$i]);
            }

            if ($this->header_type == self::OPT_HEADER_PRE_VALID || $this->header_type == self::OPT_HEADER_PRE_TABLE) {
                $parsed_code .= '</pre>';
            }
            if ($this->header_type == self::OPT_HEADER_PRE_TABLE && $this->line_numbers != self::OPT_LINE_NUMBERS_NONE) {
                $parsed_code .= '</td>';
            }
        }

        $parsed_code .= $this->footer();
    }

    /**
     * Swaps out spaces and tabs for HTML indentation. Not needed if
     * the code is in a pre block...
     *
     * @param  string The source to indent (reference!)
     */
    private function indent(&$result)
    {
        /// Replace tabs with the correct number of spaces
        if (false !== strpos($result, "\t")) {
            $lines = explode("\n", $result);
            $result = null;//Save memory while we process the lines individually
            $tab_width = $this->get_real_tab_width();
            $tab_string = '&nbsp;' . str_repeat(' ', $tab_width);

            for ($key = 0, $n = count($lines); $key < $n; $key++) {
                $line = $lines[$key];
                if (false === strpos($line, "\t")) {
                    continue;
                }

                $pos = 0;
                $length = strlen($line);
                $lines[$key] = ''; // reduce memory

                $IN_TAG = false;
                for ($i = 0; $i < $length; ++$i) {
                    $char = $line[$i];
                    // Simple engine to work out whether we're in a tag.
                    // If we are we modify $pos. This is so we ignore HTML
                    // in the line and only workout the tab replacement
                    // via the actual content of the string
                    // This test could be improved to include strings in the
                    // html so that < or > would be allowed in user's styles
                    // (e.g. quotes: '<' '>'; or similar)
                    if ($IN_TAG) {
                        if ('>' == $char) {
                            $IN_TAG = false;
                        }
                        $lines[$key] .= $char;
                    }
                    elseif ('<' == $char) {
                        $IN_TAG = true;
                        $lines[$key] .= '<';
                    }
                    elseif ('&' == $char) {
                        $substr = substr($line, $i + 3, 5);
                        $posi = strpos($substr, ';');
                        if (false === $posi) {
                            ++$pos;
                        }
                        else {
                            $pos -= $posi + 2;
                        }
                        $lines[$key] .= $char;
                    }
                    elseif ("\t" == $char) {
                        $str = '';
                        // OPTIMISE - move $strs out. Make an array:
                        // $tabs = array(
                        //  1 => '&nbsp;',
                        //  2 => '&nbsp; ',
                        //  3 => '&nbsp; &nbsp;' etc etc
                        // to use instead of building a string every time
                        $tab_end_width = $tab_width - ($pos % $tab_width); //Moved out of the look as it doesn't change within the loop
                        if (($pos & 1) || 1 == $tab_end_width) {
                            $str .= substr($tab_string, 6, $tab_end_width);
                        }
                        else {
                            $str .= substr($tab_string, 0, $tab_end_width + 5);
                        }
                        $lines[$key] .= $str;
                        $pos += $tab_end_width;

                        if (false === strpos($line, "\t", $i + 1)) {
                            $lines[$key] .= substr($line, $i + 1);
                            break;
                        }
                    }
                    elseif (0 == $pos && ' ' == $char) {
                        $lines[$key] .= '&nbsp;';
                        ++$pos;
                    }
                    else {
                        $lines[$key] .= $char;
                        ++$pos;
                    }
                }
            }
            $result = implode("\n", $lines);
            unset($lines);//We don't need the lines separated beyond this --- free them!
        }
        // Other whitespace
        // BenBE: Fix to reduce the number of replacements to be done
        $result = preg_replace('/^ /m', '&nbsp;', $result);
        $result = str_replace('  ', ' &nbsp;', $result);

        if ($this->line_numbers == self::OPT_LINE_NUMBERS_NONE && $this->header_type != self::OPT_HEADER_PRE_TABLE) {
            if ($this->line_ending === null) {
                $result = nl2br($result);
            }
            else {
                $result = str_replace("\n", $this->line_ending, $result);
            }
        }
    }

    /**
     * Returns the tab width to use, based on the current language and user
     * preference
     *
     * @return int Tab width
     */
    function get_real_tab_width()
    {
        if (!$this->use_language_tab_width ||
            !isset($this->language_data['TAB_WIDTH'])
        ) {
            return $this->tab_width;
        }
        else {
            return $this->language_data['TAB_WIDTH'];
        }
    }

    /**
     * Creates the header for the code block (with correct attributes)
     *
     * @return string The header for the code block
     */
    private function header()
    {
        // Get attributes needed
        $attributes = ' class="' . $this->genCSSName($this->language);
        if ($this->overall_class != '') {
            $attributes .= " " . $this->genCSSName($this->overall_class);
        }
        $attributes .= '"';

        if ($this->overall_id != '') {
            $attributes .= " id=\"{$this->overall_id}\"";
        }
        if ($this->overall_style != '' && !$this->use_classes) {
            $attributes .= ' style="' . $this->overall_style . '"';
        }

        $ol_attributes = '';

        if ($this->line_numbers_start != 1) {
            $ol_attributes .= ' start="' . $this->line_numbers_start . '"';
        }

        // Get the header HTML
        $header = $this->header_content;
        if ($header) {
            if ($this->header_type == self::OPT_HEADER_PRE || $this->header_type == self::OPT_HEADER_PRE_VALID) {
                $header = str_replace("\n", '', $header);
            }
            $header = $this->replace_keywords($header);

            if ($this->use_classes) {
                $attr = ' class="head"';
            }
            else {
                $attr = " style=\"{$this->header_content_style}\"";
            }
            if ($this->header_type == self::OPT_HEADER_PRE_TABLE && $this->line_numbers != self::OPT_LINE_NUMBERS_NONE) {
                $header = "<thead><tr><td colspan=\"2\" $attr>$header</td></tr></thead>";
            }
            else {
                $header = "<div$attr>$header</div>";
            }
        }

        if (self::OPT_HEADER_NONE == $this->header_type) {
            if ($this->line_numbers != self::OPT_LINE_NUMBERS_NONE) {
                return "$header<ol$attributes$ol_attributes>";
            }

            return $header . ($this->force_code_block ? '<div>' : '');
        }

        // Work out what to return and do it
        if ($this->line_numbers != self::OPT_LINE_NUMBERS_NONE) {
            if ($this->header_type == self::OPT_HEADER_PRE) {
                return "<pre$attributes>$header<ol$ol_attributes>";
            }
            elseif ($this->header_type == self::OPT_HEADER_DIV ||
                    $this->header_type == self::OPT_HEADER_PRE_VALID
            ) {
                return "<div$attributes>$header<ol$ol_attributes>";
            }
            elseif ($this->header_type == self::OPT_HEADER_PRE_TABLE) {
                return "<table$attributes>$header<tbody><tr class=\"li1\">";
            }
        }
        else {
            if ($this->header_type == self::OPT_HEADER_PRE) {
                return "<pre$attributes>$header" .
                       ($this->force_code_block ? '<div>' : '');
            }
            else {
                return "<div$attributes>$header" .
                       ($this->force_code_block ? '<div>' : '');
            }
        }
    }

    private function genCSSName($name)
    {
        return (is_numeric($name[0]) ? '_' : '') . $name;
    }

    /**
     * Replaces certain keywords in the header and footer with
     * certain configuration values
     *
     * @param  string $instr The header or footer content to do replacement on
     *
     * @return string The header or footer with replaced keywords
     * @since  1.0.2
     */
    private function replace_keywords($instr)
    {
        $keywords = $replacements = [];

        $keywords[] = '<TIME>';
        $keywords[] = '{TIME}';
        $replacements[] = $replacements[] = number_format($time = $this->get_time(), 3);

        $keywords[] = '<LANGUAGE>';
        $keywords[] = '{LANGUAGE}';
        $replacements[] = $replacements[] = $this->language_data['LANG_NAME'];

        $keywords[] = '<VERSION>';
        $keywords[] = '{VERSION}';
        $replacements[] = $replacements[] = self::VERSION;

        $keywords[] = '<SPEED>';
        $keywords[] = '{SPEED}';
        if ($time <= 0) {
            $speed = 'N/A';
        }
        else {
            $speed = strlen($this->source) / $time;
            if ($speed >= 1024) {
                $speed = sprintf("%.2f KB/s", $speed / 1024.0);
            }
            else {
                $speed = sprintf("%.0f B/s", $speed);
            }
        }
        $replacements[] = $replacements[] = $speed;

        return str_replace($keywords, $replacements, $instr);
    }

    /**
     * Gets the time taken to parse the code
     *
     * @return double The time taken to parse the code
     */
    function get_time()
    {
        return $this->time;
    }

    /**
     * Get's the style that is used for the specified line
     *
     * @param int $line The line number information is requested for
     *
     * @return string
     */
    private function get_line_style($line)
    {
        //$style = null;
        $style = null;
        if (isset($this->highlight_extra_lines_styles[$line])) {
            $style = $this->highlight_extra_lines_styles[$line];
        }
        else { // if no "extra" style assigned
            $style = $this->highlight_extra_lines_style;
        }

        return $style;
    }

    private function finalizeFancyLines($i, &$close)
    {
        $ret = '';

        if ($this->line_numbers == self::OPT_LINE_NUMBERS_FANCY &&
            $i % $this->line_nth_row == ($this->line_nth_row - 1)
        ) {
            // Set the attributes to style the line
            if ($this->use_classes) {
                $ret .= '<span class="xtra li2"><span class="de2">';
            }
            else {
                // This style "covers up" the special styles set for special lines
                // so that styles applied to special lines don't apply to the actual
                // code on that line
                $ret .= '<span style="display:block;' . $this->line_style2 . '">'
                        . '<span style="' . $this->code_style . '">';
            }
            $close += 2;
        }

        //Is this some line with extra styles???
        if (in_array($i + 1, $this->highlight_extra_lines)) {
            if ($this->use_classes) {
                if (isset($this->highlight_extra_lines_styles[$i])) {
                    $ret .= "<span class=\"xtra lx$i\">";
                }
                else {
                    $ret .= "<span class=\"xtra ln-xtra\">";
                }
            }
            else {
                $ret .= "<span style=\"display:block;" . $this->get_line_style($i) . "\">";
            }
            ++$close;
        }

        return $ret;
    }

    /**
     * Returns the footer for the code block.
     *
     * @return string The footer for the code block
     */
    private function footer()
    {
        $footer = $this->footer_content;
        if ($footer) {
            if ($this->header_type == self::OPT_HEADER_PRE) {
                $footer = str_replace("\n", '', $footer);;
            }
            $footer = $this->replace_keywords($footer);

            if ($this->use_classes) {
                $attr = ' class="foot"';
            }
            else {
                $attr = " style=\"{$this->footer_content_style}\"";
            }
            if ($this->header_type == self::OPT_HEADER_PRE_TABLE && $this->line_numbers != self::OPT_LINE_NUMBERS_NONE) {
                $footer = "<tfoot><tr><td colspan=\"2\">$footer</td></tr></tfoot>";
            }
            else {
                $footer = "<div$attr>$footer</div>";
            }
        }

        if (self::OPT_HEADER_NONE == $this->header_type) {
            return ($this->line_numbers != self::OPT_LINE_NUMBERS_NONE) ? '</ol>' . $footer : $footer;
        }

        if ($this->header_type == self::OPT_HEADER_DIV || $this->header_type == self::OPT_HEADER_PRE_VALID) {
            if ($this->line_numbers != self::OPT_LINE_NUMBERS_NONE) {
                return "</ol>$footer</div>";
            }

            return ($this->force_code_block ? '</div>' : '') .
                   "$footer</div>";
        }
        elseif ($this->header_type == self::OPT_HEADER_PRE_TABLE) {
            if ($this->line_numbers != self::OPT_LINE_NUMBERS_NONE) {
                return "</tr></tbody>$footer</table>";
            }

            return ($this->force_code_block ? '</div>' : '') .
                   "$footer</div>";
        }
        else {
            if ($this->line_numbers != self::OPT_LINE_NUMBERS_NONE) {
                return "</ol>$footer</pre>";
            }

            return ($this->force_code_block ? '</div>' : '') .
                   "$footer</pre>";
        }
    }

    /**
     * Setup caches needed for parsing. This is automatically called in parse_code() when appropriate.
     * This function makes stylesheet generators much faster as they do not need these caches.
     */
    private function build_parse_cache()
    {
        // cache symbol regexp
        //As this is a costy operation, we avoid doing it for multiple groups ...
        //Instead we perform it for all symbols at once.
        //
        //For this to work, we need to reorganize the data arrays.
        if ($this->lexic_permissions['SYMBOLS'] && !empty($this->language_data['SYMBOLS'])) {
            $this->language_data['MULTIPLE_SYMBOL_GROUPS'] = count($this->language_data['STYLES']['SYMBOLS']) > 1;

            $this->language_data['SYMBOL_DATA'] = [];
            $symbol_preg_multi = []; // multi char symbols
            $symbol_preg_single = []; // single char symbols
            foreach ($this->language_data['SYMBOLS'] as $key => $symbols) {
                if (is_array($symbols)) {
                    foreach ($symbols as $sym) {
                        $sym = $this->hsc($sym);
                        if (!isset($this->language_data['SYMBOL_DATA'][$sym])) {
                            $this->language_data['SYMBOL_DATA'][$sym] = $key;
                            if (isset($sym[1])) { // multiple chars
                                $symbol_preg_multi[] = preg_quote($sym, '/');
                            }
                            else { // single char
                                if ($sym == '-') {
                                    // don't trigger range out of order error
                                    $symbol_preg_single[] = '\-';
                                }
                                else {
                                    $symbol_preg_single[] = preg_quote($sym, '/');
                                }
                            }
                        }
                    }
                }
                else {
                    $symbols = $this->hsc($symbols);
                    if (!isset($this->language_data['SYMBOL_DATA'][$symbols])) {
                        $this->language_data['SYMBOL_DATA'][$symbols] = 0;
                        if (isset($symbols[1])) { // multiple chars
                            $symbol_preg_multi[] = preg_quote($symbols, '/');
                        }
                        elseif ($symbols == '-') {
                            // don't trigger range out of order error
                            $symbol_preg_single[] = '\-';
                        }
                        else { // single char
                            $symbol_preg_single[] = preg_quote($symbols, '/');
                        }
                    }
                }
            }

            //Now we have an array with each possible symbol as the key and the style as the actual data.
            //This way we can set the correct style just the moment we highlight ...
            //
            //Now we need to rewrite our array to get a search string that
            $symbol_preg = [];
            if (!empty($symbol_preg_multi)) {
                rsort($symbol_preg_multi);
                $symbol_preg[] = implode('|', $symbol_preg_multi);
            }
            if (!empty($symbol_preg_single)) {
                rsort($symbol_preg_single);
                $symbol_preg[] = '[' . implode('', $symbol_preg_single) . ']';
            }
            $this->language_data['SYMBOL_SEARCH'] = implode("|", $symbol_preg);
        }

        // cache optimized regexp for keyword matching
        // remove old cache
        $this->language_data['CACHED_KEYWORD_LISTS'] = [];
        foreach (array_keys($this->language_data['KEYWORDS']) as $key) {
            if (!isset($this->lexic_permissions['KEYWORDS'][$key]) ||
                $this->lexic_permissions['KEYWORDS'][$key]
            ) {
                $this->optimize_keyword_group($key);
            }
        }

        // brackets
        if ($this->lexic_permissions['BRACKETS']) {
            $this->language_data['CACHE_BRACKET_MATCH'] = ['[', ']', '(', ')', '{', '}'];
            if (!$this->use_classes && isset($this->language_data['STYLES']['BRACKETS'][0])) {
                $this->language_data['CACHE_BRACKET_REPLACE'] = [
                    '<| style="' . $this->language_data['STYLES']['BRACKETS'][0] . '">&#91;|>',
                    '<| style="' . $this->language_data['STYLES']['BRACKETS'][0] . '">&#93;|>',
                    '<| style="' . $this->language_data['STYLES']['BRACKETS'][0] . '">&#40;|>',
                    '<| style="' . $this->language_data['STYLES']['BRACKETS'][0] . '">&#41;|>',
                    '<| style="' . $this->language_data['STYLES']['BRACKETS'][0] . '">&#123;|>',
                    '<| style="' . $this->language_data['STYLES']['BRACKETS'][0] . '">&#125;|>',
                ];
            }
            else {
                $this->language_data['CACHE_BRACKET_REPLACE'] = [
                    '<| class="br0">&#91;|>',
                    '<| class="br0">&#93;|>',
                    '<| class="br0">&#40;|>',
                    '<| class="br0">&#41;|>',
                    '<| class="br0">&#123;|>',
                    '<| class="br0">&#125;|>',
                ];
            }
        }

        //Build the parse cache needed to highlight numbers appropriate
        if ($this->lexic_permissions['NUMBERS']) {
            //Check if the style rearrangements have been processed ...
            //This also does some preprocessing to check which style groups are useable ...
            if (!isset($this->language_data['NUMBERS_CACHE'])) {
                $this->build_style_cache();
            }

            //Number format specification
            //All this formats are matched case-insensitively!
            static $numbers_format = [
                self::NUMBER_INT_BASIC          =>
                    '(?:(?<![0-9a-z_\.%$@])|(?<=\.\.))(?<![\d\.]e[+\-])([1-9]\d*?|0)(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_INT_CSTYLE         =>
                    '(?<![0-9a-z_\.%])(?<![\d\.]e[+\-])([1-9]\d*?|0)l(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_BIN_SUFFIX         =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])[01]+?[bB](?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_BIN_PREFIX_PERCENT =>
                    '(?<![0-9a-z_\.%])(?<![\d\.]e[+\-])%[01]+?(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_BIN_PREFIX_0B      =>
                    '(?<![0-9a-z_\.%])(?<![\d\.]e[+\-])0b[01]+?(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_OCT_PREFIX         =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])0[0-7]+?(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_OCT_PREFIX_0O      =>
                    '(?<![0-9a-z_\.%])(?<![\d\.]e[+\-])0o[0-7]+?(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_OCT_PREFIX_AT      =>
                    '(?<![0-9a-z_\.%])(?<![\d\.]e[+\-])\@[0-7]+?(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_OCT_SUFFIX         =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])[0-7]+?o(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_HEX_PREFIX         =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])0x[0-9a-fA-F]+?(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_HEX_PREFIX_DOLLAR  =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])\$[0-9a-fA-F]+?(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_HEX_SUFFIX         =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])\d[0-9a-fA-F]*?[hH](?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_FLT_NONSCI         =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])\d+?\.\d+?(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_FLT_NONSCI_F       =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])(?:\d+?(?:\.\d*?)?|\.\d+?)f(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_FLT_SCI_SHORT      =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])\.\d+?(?:e[+\-]?\d+?)?(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
                self::NUMBER_FLT_SCI_ZERO       =>
                    '(?<![0-9a-z_\.])(?<![\d\.]e[+\-])(?:\d+?(?:\.\d*?)?|\.\d+?)(?:e[+\-]?\d+?)?(?![0-9a-z]|\.(?:[eE][+\-]?)?\d)',
            ];

            //At this step we have an associative array with flag groups for a
            //specific style or an string denoting a regexp given its index.
            $this->language_data['NUMBERS_RXCACHE'] = [];
            foreach ($this->language_data['NUMBERS_CACHE'] as $key => $rxdata) {
                if (is_string($rxdata)) {
                    $regexp = $rxdata;
                }
                else {
                    //This is a bitfield of number flags to highlight:
                    //Build an array, implode them together and make this the actual RX
                    $rxuse = [];
                    for ($i = 1; $i <= $rxdata; $i <<= 1) {
                        if ($rxdata & $i) {
                            $rxuse[] = $numbers_format[$i];
                        }
                    }
                    $regexp = implode("|", $rxuse);
                }

                $this->language_data['NUMBERS_RXCACHE'][$key] =
                    "/(?<!<\|\/)(?<!<\|!REG3XP)(?<!<\|\/NUM!)(?<!\d\/>)($regexp)(?!(?:<DOT>|(?>[^\<]))+>)(?![^<]*>)(?!\|>)(?!\/>)/i"; //
            }

            if (!isset($this->language_data['PARSER_CONTROL']['NUMBERS']['PRECHECK_RX'])) {
                $this->language_data['PARSER_CONTROL']['NUMBERS']['PRECHECK_RX'] = '#\d#';
            }
        }

        $this->parse_cache_built = true;
    }

    /**
     * Setup caches needed for styling. This is automatically called in
     * parse_code() and get_stylesheet() when appropriate. This function helps
     * stylesheet generators as they rely on some style information being
     * preprocessed
     */
    private function build_style_cache()
    {
        //Build the style cache needed to highlight numbers appropriate
        if ($this->lexic_permissions['NUMBERS']) {
            //First check what way highlighting information for numbers are given
            if (!isset($this->language_data['NUMBERS'])) {
                $this->language_data['NUMBERS'] = 0;
            }

            if (is_array($this->language_data['NUMBERS'])) {
                $this->language_data['NUMBERS_CACHE'] = $this->language_data['NUMBERS'];
            }
            else {
                $this->language_data['NUMBERS_CACHE'] = [];
                if (!$this->language_data['NUMBERS']) {
                    $this->language_data['NUMBERS'] =
                        self::NUMBER_INT_BASIC |
                        self::NUMBER_FLT_NONSCI;
                }

                for ($i = 0, $j = $this->language_data['NUMBERS']; $j > 0; ++$i, $j >>= 1) {
                    //Rearrange style indices if required ...
                    if (isset($this->language_data['STYLES']['NUMBERS'][1 << $i])) {
                        $this->language_data['STYLES']['NUMBERS'][$i] =
                            $this->language_data['STYLES']['NUMBERS'][1 << $i];
                        unset($this->language_data['STYLES']['NUMBERS'][1 << $i]);
                    }

                    //Check if this bit is set for highlighting
                    if ($j & 1) {
                        //So this bit is set ...
                        //Check if it belongs to group 0 or the actual stylegroup
                        if (isset($this->language_data['STYLES']['NUMBERS'][$i])) {
                            $this->language_data['NUMBERS_CACHE'][$i] = 1 << $i;
                        }
                        else {
                            if (!isset($this->language_data['NUMBERS_CACHE'][0])) {
                                $this->language_data['NUMBERS_CACHE'][0] = 0;
                            }
                            $this->language_data['NUMBERS_CACHE'][0] |= 1 << $i;
                        }
                    }
                }
            }
        }
    }

    /**
     * Takes a string that has no strings or comments in it, and highlights
     * stuff like keywords, numbers and methods.
     *
     * @param string The string to parse for keyword, numbers etc.
     *
     * @return string
     * @todo BUGGY! Why? Why not build string and return?
     */
    private function parse_non_string_part($stuff_to_parse)
    {
        $stuff_to_parse = ' ' . $this->hsc($stuff_to_parse);

        // Highlight keywords
        $disallowed_before = "(?<![a-zA-Z0-9\$_\|\#|^&";
        $disallowed_after = "(?![a-zA-Z0-9_\|%\\-&;";
        if ($this->lexic_permissions['STRINGS']) {
            $quotemarks = preg_quote(implode($this->language_data['QUOTEMARKS']), '/');
            $disallowed_before .= $quotemarks;
            $disallowed_after .= $quotemarks;
        }
        $disallowed_before .= "])";
        $disallowed_after .= "])";

        $parser_control_pergroup = false;
        if (isset($this->language_data['PARSER_CONTROL'])) {
            if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS'])) {
                $x = 0; // check wether per-keyword-group parser_control is enabled
                if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS']['DISALLOWED_BEFORE'])) {
                    $disallowed_before = $this->language_data['PARSER_CONTROL']['KEYWORDS']['DISALLOWED_BEFORE'];
                    ++$x;
                }
                if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS']['DISALLOWED_AFTER'])) {
                    $disallowed_after = $this->language_data['PARSER_CONTROL']['KEYWORDS']['DISALLOWED_AFTER'];
                    ++$x;
                }
                $parser_control_pergroup = (count($this->language_data['PARSER_CONTROL']['KEYWORDS']) - $x) > 0;
            }
        }

        foreach (array_keys($this->language_data['KEYWORDS']) as $k) {
            if (!isset($this->lexic_permissions['KEYWORDS'][$k]) ||
                $this->lexic_permissions['KEYWORDS'][$k]
            ) {

                $case_sensitive = $this->language_data['CASE_SENSITIVE'][$k];
                $modifiers = $case_sensitive ? '' : 'i';

                // NEW in 1.0.8 - per-keyword-group parser control
                $disallowed_before_local = $disallowed_before;
                $disallowed_after_local = $disallowed_after;
                if ($parser_control_pergroup && isset($this->language_data['PARSER_CONTROL']['KEYWORDS'][$k])) {
                    if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS'][$k]['DISALLOWED_BEFORE'])) {
                        $disallowed_before_local =
                            $this->language_data['PARSER_CONTROL']['KEYWORDS'][$k]['DISALLOWED_BEFORE'];
                    }

                    if (isset($this->language_data['PARSER_CONTROL']['KEYWORDS'][$k]['DISALLOWED_AFTER'])) {
                        $disallowed_after_local =
                            $this->language_data['PARSER_CONTROL']['KEYWORDS'][$k]['DISALLOWED_AFTER'];
                    }
                }

                $this->_kw_replace_group = $k;

                //NEW in 1.0.8, the cached regexp list
                // since we don't want PHP / PCRE to crash due to too large patterns we split them into smaller chunks
                for ($set = 0, $set_length = count($this->language_data['CACHED_KEYWORD_LISTS'][$k]); $set < $set_length; ++$set) {
                    $keywordset =& $this->language_data['CACHED_KEYWORD_LISTS'][$k][$set];
                    // Might make a more unique string for putting the number in soon
                    // Basically, we don't put the styles in yet because then the styles themselves will
                    // get highlighted if the language has a CSS keyword in it (like CSS, for example ;))
                    $stuff_to_parse = preg_replace_callback(
                        "/$disallowed_before_local({$keywordset})(?!\<DOT\>(?:htm|php|aspx?))$disallowed_after_local/$modifiers",
                        [$this, 'handle_keyword_replace'],
                        $stuff_to_parse
                    );
                }
            }
        }

        // Regular expressions
        foreach ($this->language_data['REGEXPS'] as $key => $regexp) {
            if ($this->lexic_permissions['REGEXPS'][$key]) {
                if (is_array($regexp)) {
                    if ($this->line_numbers != self::OPT_LINE_NUMBERS_NONE) {
                        // produce valid HTML when we match multiple lines
                        $this->_hmr_replace = $regexp[self::REPLACE];
                        $this->_hmr_before = $regexp[self::BEFORE];
                        $this->_hmr_key = $key;
                        $this->_hmr_after = $regexp[self::AFTER];
                        $stuff_to_parse = preg_replace_callback(
                            "/" . $regexp[self::SEARCH] . "/{$regexp[self::MODIFIERS]}",
                            [$this, 'handle_multiline_regexps'],
                            $stuff_to_parse);
                        $this->_hmr_replace = false;
                        $this->_hmr_before = '';
                        $this->_hmr_after = '';
                    }
                    else {
                        $stuff_to_parse = preg_replace(
                            '/' . $regexp[self::SEARCH] . '/' . $regexp[self::MODIFIERS],
                            $regexp[self::BEFORE] . '<|!REG3XP' . $key . '!>' . $regexp[self::REPLACE] . '|>' . $regexp[self::AFTER],
                            $stuff_to_parse);
                    }
                }
                else {
                    if ($this->line_numbers != self::OPT_LINE_NUMBERS_NONE) {
                        // produce valid HTML when we match multiple lines
                        $this->_hmr_key = $key;
                        $stuff_to_parse = preg_replace_callback("/(" . $regexp . ")/",
                                                                [$this, 'handle_multiline_regexps'], $stuff_to_parse);
                        $this->_hmr_key = '';
                    }
                    else {
                        $stuff_to_parse = preg_replace("/(" . $regexp . ")/", "<|!REG3XP$key!>\\1|>", $stuff_to_parse);
                    }
                }
            }
        }

        // Highlight numbers. As of 1.0.8 we support different types of numbers
        $numbers_found = false;

        if ($this->lexic_permissions['NUMBERS'] && preg_match($this->language_data['PARSER_CONTROL']['NUMBERS']['PRECHECK_RX'], $stuff_to_parse)) {
            $numbers_found = true;

            //For each of the formats ...
            foreach ($this->language_data['NUMBERS_RXCACHE'] as $id => $regexp) {
                //Check if it should be highlighted ...
                $stuff_to_parse = preg_replace($regexp, "<|/NUM!$id/>\\1|>", $stuff_to_parse);
            }
        }

        //
        // Now that's all done, replace /[number]/ with the correct styles
        //
        foreach (array_keys($this->language_data['KEYWORDS']) as $k) {
            if (!$this->use_classes) {
                $attributes = ' style="' .
                              (isset($this->language_data['STYLES']['KEYWORDS'][$k]) ?
                                  $this->language_data['STYLES']['KEYWORDS'][$k] : "") . '"';
            }
            else {
                $attributes = ' class="kw' . $k . '"';
            }
            $stuff_to_parse = str_replace("<|/$k/>", "<|$attributes>", $stuff_to_parse);
        }

        if ($numbers_found) {
            // Put number styles in
            foreach ($this->language_data['NUMBERS_RXCACHE'] as $id => $regexp) {
                //Commented out for now, as this needs some review ...
                //                if ($numbers_permissions & $id) {
                //Get the appropriate style ...
                //Checking for unset styles is done by the style cache builder ...
                if (!$this->use_classes) {
                    $attributes = ' style="' . $this->language_data['STYLES']['NUMBERS'][$id] . '"';
                }
                else {
                    $attributes = ' class="nu' . $id . '"';
                }

                //Set in the correct styles ...
                $stuff_to_parse = str_replace("/NUM!$id/", $attributes, $stuff_to_parse);
                //                }
            }
        }

        // Highlight methods and fields in objects
        if ($this->lexic_permissions['METHODS'] && $this->language_data['OOLANG']) {
            $oolang_spaces = "[\s]*";
            $oolang_before = "";
            $oolang_after = "[a-zA-Z][a-zA-Z0-9_]*";
            if (isset($this->language_data['PARSER_CONTROL'])) {
                if (isset($this->language_data['PARSER_CONTROL']['OOLANG'])) {
                    if (isset($this->language_data['PARSER_CONTROL']['OOLANG']['MATCH_BEFORE'])) {
                        $oolang_before = $this->language_data['PARSER_CONTROL']['OOLANG']['MATCH_BEFORE'];
                    }
                    if (isset($this->language_data['PARSER_CONTROL']['OOLANG']['MATCH_AFTER'])) {
                        $oolang_after = $this->language_data['PARSER_CONTROL']['OOLANG']['MATCH_AFTER'];
                    }
                    if (isset($this->language_data['PARSER_CONTROL']['OOLANG']['MATCH_SPACES'])) {
                        $oolang_spaces = $this->language_data['PARSER_CONTROL']['OOLANG']['MATCH_SPACES'];
                    }
                }
            }

            foreach ($this->language_data['OBJECT_SPLITTERS'] as $key => $splitter) {
                if (false !== strpos($stuff_to_parse, $splitter)) {
                    if (!$this->use_classes) {
                        $attributes = ' style="' . $this->language_data['STYLES']['METHODS'][$key] . '"';
                    }
                    else {
                        $attributes = ' class="me' . $key . '"';
                    }
                    $stuff_to_parse = preg_replace("/($oolang_before)(" . preg_quote($this->language_data['OBJECT_SPLITTERS'][$key], '/') . ")($oolang_spaces)($oolang_after)/", "\\1\\2\\3<|$attributes>\\4|>", $stuff_to_parse);
                }
            }
        }

        //
        // Highlight brackets. Yes, I've tried adding a semi-colon to this list.
        // You try it, and see what happens ;)
        // TODO: Fix lexic permissions not converting entities if shouldn't
        // be highlighting regardless
        //
        if ($this->lexic_permissions['BRACKETS']) {
            $stuff_to_parse = str_replace($this->language_data['CACHE_BRACKET_MATCH'],
                                          $this->language_data['CACHE_BRACKET_REPLACE'], $stuff_to_parse);
        }


        //FIX for symbol highlighting ...
        if ($this->lexic_permissions['SYMBOLS'] && !empty($this->language_data['SYMBOLS'])) {
            //Get all matches and throw away those witin a block that is already highlighted... (i.e. matched by a regexp)
            $n_symbols = preg_match_all("/<\|(?:<DOT>|[^>])+>(?:(?!\|>).*?)\|>|<\/a>|(?:" . $this->language_data['SYMBOL_SEARCH'] . ")+(?![^<]+?>)/", $stuff_to_parse, $pot_symbols, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            $global_offset = 0;
            for ($s_id = 0; $s_id < $n_symbols; ++$s_id) {
                $symbol_match = $pot_symbols[$s_id][0][0];
                if (strpos($symbol_match, '<') !== false || strpos($symbol_match, '>') !== false) {
                    // already highlighted blocks _must_ include either < or >
                    // so if this conditional applies, we have to skip this match
                    // BenBE: UNLESS the block contains <SEMI> or <PIPE>
                    if (strpos($symbol_match, '<SEMI>') === false &&
                        strpos($symbol_match, '<PIPE>') === false
                    ) {
                        continue;
                    }
                }

                // if we reach this point, we have a valid match which needs to be highlighted

                $symbol_length = strlen($symbol_match);
                $symbol_offset = $pot_symbols[$s_id][0][1];
                unset($pot_symbols[$s_id]);
                $symbol_end = $symbol_length + $symbol_offset;
                $symbol_hl = "";

                // if we have multiple styles, we have to handle them properly
                if ($this->language_data['MULTIPLE_SYMBOL_GROUPS']) {
                    $old_sym = -1;
                    // Split the current stuff to replace into its atomic symbols ...
                    preg_match_all("/" . $this->language_data['SYMBOL_SEARCH'] . "/", $symbol_match, $sym_match_syms, PREG_PATTERN_ORDER);
                    foreach ($sym_match_syms[0] as $sym_ms) {
                        //Check if consequtive symbols belong to the same group to save output ...
                        if (isset($this->language_data['SYMBOL_DATA'][$sym_ms])
                            && ($this->language_data['SYMBOL_DATA'][$sym_ms] != $old_sym)
                        ) {
                            if (-1 != $old_sym) {
                                $symbol_hl .= "|>";
                            }
                            $old_sym = $this->language_data['SYMBOL_DATA'][$sym_ms];
                            if (!$this->use_classes) {
                                $symbol_hl .= '<| style="' . $this->language_data['STYLES']['SYMBOLS'][$old_sym] . '">';
                            }
                            else {
                                $symbol_hl .= '<| class="sy' . $old_sym . '">';
                            }
                        }
                        $symbol_hl .= $sym_ms;
                    }
                    unset($sym_match_syms);

                    //Close remaining tags and insert the replacement at the right position ...
                    //Take caution if symbol_hl is empty to avoid doubled closing spans.
                    if (-1 != $old_sym) {
                        $symbol_hl .= "|>";
                    }
                }
                else {
                    if (!$this->use_classes) {
                        $symbol_hl = '<| style="' . $this->language_data['STYLES']['SYMBOLS'][0] . '">';
                    }
                    else {
                        $symbol_hl = '<| class="sy0">';
                    }
                    $symbol_hl .= $symbol_match . '|>';
                }

                $stuff_to_parse = substr_replace($stuff_to_parse, $symbol_hl, $symbol_offset + $global_offset, $symbol_length);

                // since we replace old text with something of different size,
                // we'll have to keep track of the differences
                $global_offset += strlen($symbol_hl) - $symbol_length;
            }
        }
        //FIX for symbol highlighting ...

        // Add class/style for regexps
        foreach (array_keys($this->language_data['REGEXPS']) as $key) {
            if ($this->lexic_permissions['REGEXPS'][$key]) {
                if (is_callable($this->language_data['STYLES']['REGEXPS'][$key])) {
                    $this->_rx_key = $key;
                    $stuff_to_parse = preg_replace_callback("/!REG3XP$key!(.*)\|>/U",
                                                            [$this, 'handle_regexps_callback'],
                                                            $stuff_to_parse);
                }
                else {
                    if (!$this->use_classes) {
                        $attributes = ' style="' . $this->language_data['STYLES']['REGEXPS'][$key] . '"';
                    }
                    else {
                        if (is_array($this->language_data['REGEXPS'][$key]) &&
                            array_key_exists(self::CLASS, $this->language_data['REGEXPS'][$key])
                        ) {
                            $attributes = ' class="' .
                                          $this->language_data['REGEXPS'][$key][self::CLASS] . '"';
                        }
                        else {
                            $attributes = ' class="re' . $key . '"';
                        }
                    }
                    $stuff_to_parse = str_replace("!REG3XP$key!", "$attributes", $stuff_to_parse);
                }
            }
        }

        // Replace <DOT> with . for urls
        $stuff_to_parse = str_replace('<DOT>', '.', $stuff_to_parse);
        // Replace <|UR1| with <a href= for urls also
        if (isset($this->link_styles[self::LINK])) {
            if ($this->use_classes) {
                $stuff_to_parse = str_replace('<|UR1|', '<a' . $this->link_target . ' href=', $stuff_to_parse);
            }
            else {
                $stuff_to_parse = str_replace('<|UR1|', '<a' . $this->link_target . ' style="' . $this->link_styles[self::LINK] . '" href=', $stuff_to_parse);
            }
        }
        else {
            $stuff_to_parse = str_replace('<|UR1|', '<a' . $this->link_target . ' href=', $stuff_to_parse);
        }

        //
        // NOW we add the span thingy ;)
        //

        $stuff_to_parse = str_replace('<|', '<span', $stuff_to_parse);
        $stuff_to_parse = str_replace('|>', '</span>', $stuff_to_parse);

        return substr($stuff_to_parse, 1);
    }

    /**
     * Changes the case of a keyword for those languages where a change is asked for
     *
     * @param  string The keyword to change the case of
     *
     * @return string The keyword with its case changed
     */
    private function change_case($instr)
    {
        switch ($this->language_data['CASE_KEYWORDS']) {
            case self::OPT_CAPS_UPPER:
                return strtoupper($instr);
            case self::OPT_CAPS_LOWER:
                return strtolower($instr);
            default:
                return $instr;
        }
    }

    /**
     * Returns a stylesheet for the highlighted code. If $economy mode
     * is true, we only return the stylesheet declarations that matter for
     * this code block instead of the whole thing
     *
     * @param boolean $economy_mode Whether to use economy mode or not
     *
     * @return string A stylesheet built on the data for the current language
     */
    function get_stylesheet($economy_mode = true)
    {
        // If there's an error, chances are that the language file
        // won't have populated the language data file, so we can't
        // risk getting a stylesheet...
        if ($this->error) {
            return '';
        }

        //Check if the style rearrangements have been processed ...
        //This also does some preprocessing to check which style groups are useable ...
        if (!isset($this->language_data['NUMBERS_CACHE'])) {
            $this->build_style_cache();
        }

        // First, work out what the selector should be. If there's an ID,
        // that should be used, the same for a class. Otherwise, a selector
        // of '' means that these styles will be applied anywhere
        if ($this->overall_id) {
            $selector = '#' . $this->genCSSName($this->overall_id);
        }
        else {
            $selector = '.' . $this->genCSSName($this->language);
            if ($this->overall_class) {
                $selector .= '.' . $this->genCSSName($this->overall_class);
            }
        }
        $selector .= ' ';

        // Header of the stylesheet
        if (!$economy_mode) {
            $stylesheet = "/**\n" .
                          " * GenSynth Dynamically Generated Stylesheet\n" .
                          " * --------------------------------------\n" .
                          " * Dynamically generated stylesheet for {$this->language}\n" .
                          " * CSS class: {$this->overall_class}, CSS id: {$this->overall_id}\n" .
                          " * GenSynth (C) 2014 - 2015 Ryan Pallas\n" .
                          " * (http://qbnz.com/highlighter/)\n" .
                          " * --------------------------------------\n" .
                          " */\n";
        }
        else {
            $stylesheet = "/**\n" .
                          " * GenSynth (C) 2014 - 2015 Ryan Pallas\n" .
                          " * (http://qbnz.com/highlighter/)\n" .
                          " */\n";
        }

        // Set the <ol> to have no effect at all if there are line numbers
        // (<ol>s have margins that should be destroyed so all layout is
        // controlled by the set_overall_style method, which works on the
        // <pre> or <div> container). Additionally, set default styles for lines
        if (!$economy_mode || $this->line_numbers != self::OPT_LINE_NUMBERS_NONE) {
            //$stylesheet .= "$selector, {$selector}ol, {$selector}ol li {margin: 0;}\n";
            $stylesheet .= "$selector.de1, $selector.de2 {{$this->code_style}}\n";
        }

        // Add overall styles
        // note: neglect economy_mode, empty styles are meaningless
        if ($this->overall_style != '') {
            $stylesheet .= "$selector {{$this->overall_style}}\n";
        }

        // Add styles for links
        // note: economy mode does not make _any_ sense here
        //       either the style is empty and thus no selector is needed
        //       or the appropriate key is given.
        foreach ($this->link_styles as $key => $style) {
            if ($style != '') {
                switch ($key) {
                    case self::LINK:
                        $stylesheet .= "{$selector}a:link {{$style}}\n";
                        break;
                    case self::HOVER:
                        $stylesheet .= "{$selector}a:hover {{$style}}\n";
                        break;
                    case self::ACTIVE:
                        $stylesheet .= "{$selector}a:active {{$style}}\n";
                        break;
                    case self::VISITED:
                        $stylesheet .= "{$selector}a:visited {{$style}}\n";
                        break;
                }
            }
        }

        // Header and footer
        // note: neglect economy_mode, empty styles are meaningless
        if ($this->header_content_style != '') {
            $stylesheet .= "$selector.head {{$this->header_content_style}}\n";
        }
        if ($this->footer_content_style != '') {
            $stylesheet .= "$selector.foot {{$this->footer_content_style}}\n";
        }

        // Simple line number styles
        if ((!$economy_mode || $this->line_numbers != self::OPT_LINE_NUMBERS_NONE) && $this->line_style1 != '') {
            $stylesheet .= "{$selector}li, {$selector}.li1 {{$this->line_style1}}\n";
        }
        if ((!$economy_mode || $this->line_numbers != self::OPT_LINE_NUMBERS_NONE) && $this->table_linenumber_style != '') {
            $stylesheet .= "{$selector}.ln {{$this->table_linenumber_style}}\n";
        }
        // If there is a style set for fancy line numbers, echo it out
        if ((!$economy_mode || $this->line_numbers == self::OPT_LINE_NUMBERS_FANCY) && $this->line_style2 != '') {
            $stylesheet .= "{$selector}.li2 {{$this->line_style2}}\n";
        }

        // note: empty styles are meaningless
        foreach ($this->language_data['STYLES']['KEYWORDS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode ||
                                  (isset($this->lexic_permissions['KEYWORDS'][$group]) &&
                                   $this->lexic_permissions['KEYWORDS'][$group]))
            ) {
                $stylesheet .= "$selector.kw$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['COMMENTS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode ||
                                  (isset($this->lexic_permissions['COMMENTS'][$group]) &&
                                   $this->lexic_permissions['COMMENTS'][$group]) ||
                                  (!empty($this->language_data['COMMENT_REGEXP']) &&
                                   !empty($this->language_data['COMMENT_REGEXP'][$group])))
            ) {
                $stylesheet .= "$selector.co$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['ESCAPE_CHAR'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode || $this->lexic_permissions['ESCAPE_CHAR'])) {
                // NEW: since 1.0.8 we have to handle hardescapes
                if ($group === 'HARD') {
                    $group = '_h';
                }
                $stylesheet .= "$selector.es$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['BRACKETS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode || $this->lexic_permissions['BRACKETS'])) {
                $stylesheet .= "$selector.br$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['SYMBOLS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode || $this->lexic_permissions['SYMBOLS'])) {
                $stylesheet .= "$selector.sy$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['STRINGS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode || $this->lexic_permissions['STRINGS'])) {
                // NEW: since 1.0.8 we have to handle hardquotes
                if ($group === 'HARD') {
                    $group = '_h';
                }
                $stylesheet .= "$selector.st$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['NUMBERS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode || $this->lexic_permissions['NUMBERS'])) {
                $stylesheet .= "$selector.nu$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['METHODS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode || $this->lexic_permissions['METHODS'])) {
                $stylesheet .= "$selector.me$group {{$styles}}\n";
            }
        }
        // note: neglect economy_mode, empty styles are meaningless
        foreach ($this->language_data['STYLES']['SCRIPT'] as $group => $styles) {
            if ($styles != '') {
                $stylesheet .= "$selector.sc$group {{$styles}}\n";
            }
        }
        foreach ($this->language_data['STYLES']['REGEXPS'] as $group => $styles) {
            if ($styles != '' && (!$economy_mode ||
                                  (isset($this->lexic_permissions['REGEXPS'][$group]) &&
                                   $this->lexic_permissions['REGEXPS'][$group]))
            ) {
                if (is_array($this->language_data['REGEXPS'][$group]) &&
                    array_key_exists(self::CLASS, $this->language_data['REGEXPS'][$group])
                ) {
                    $stylesheet .= "$selector.";
                    $stylesheet .= $this->language_data['REGEXPS'][$group][self::CLASS];
                    $stylesheet .= " {{$styles}}\n";
                }
                else {
                    $stylesheet .= "$selector.re$group {{$styles}}\n";
                }
            }
        }
        // Styles for lines being highlighted extra
        if (!$economy_mode || (count($this->highlight_extra_lines) != count($this->highlight_extra_lines_styles))) {
            $stylesheet .= "{$selector}.ln-xtra, {$selector}li.ln-xtra, {$selector}div.ln-xtra {{$this->highlight_extra_lines_style}}\n";
        }
        $stylesheet .= "{$selector}span.xtra { display:block; }\n";
        foreach ($this->highlight_extra_lines_styles as $lineid => $linestyle) {
            $stylesheet .= "{$selector}.lx$lineid, {$selector}li.lx$lineid, {$selector}div.lx$lineid {{$linestyle}}\n";
        }

        return $stylesheet;
    }

    /**
     * Handles replacements of keywords to include markup and links if requested
     *
     * @param  string The keyword to add the Markup to
     *
     * @return The HTML for the match found
     *
     * @todo   Get rid of ender in keyword links
     */
    private function handle_keyword_replace($match)
    {
        $k = $this->_kw_replace_group;
        $keyword = $match[0];
        $keyword_match = $match[1];

        $before = '';
        $after = '';

        if ($this->keyword_links) {
            // Keyword links have been ebabled

            if (isset($this->language_data['URLS'][$k]) &&
                $this->language_data['URLS'][$k] != ''
            ) {
                // There is a base group for this keyword

                // Old system: strtolower
                //$keyword = ( $this->language_data['CASE_SENSITIVE'][$group] ) ? $keyword : strtolower($keyword);
                // New system: get keyword from language file to get correct case
                if (!$this->language_data['CASE_SENSITIVE'][$k] &&
                    strpos($this->language_data['URLS'][$k], '{FNAME}') !== false
                ) {
                    foreach ($this->language_data['KEYWORDS'][$k] as $word) {
                        if (strcasecmp($word, $keyword_match) == 0) {
                            break;
                        }
                    }
                }
                else {
                    $word = $keyword_match;
                }

                $before = '<|UR1|"' .
                          str_replace(
                              [
                                  '{FNAME}',
                                  '{FNAMEL}',
                                  '{FNAMEU}',
                                  '.'],
                              [
                                  str_replace('+', '%20', urlencode($this->hsc($word))),
                                  str_replace('+', '%20', urlencode($this->hsc(strtolower($word)))),
                                  str_replace('+', '%20', urlencode($this->hsc(strtoupper($word)))),
                                  '<DOT>'],
                              $this->language_data['URLS'][$k]
                          ) . '">';
                $after = '</a>';
            }
        }

        return $before . '<|/' . $k . '/>' . $this->change_case($keyword) . '|>' . $after;
    }

    /**
     * handles regular expressions highlighting-definitions with callback functions
     *
     * @note this is a callback, don't use it directly
     *
     * @param array the matches array
     *
     * @return The highlighted string
     */
    private function handle_regexps_callback($matches)
    {
        // before: "' style=\"' . call_user_func(\"$func\", '\\1') . '\"\\1|>'",
        return ' style="' . call_user_func($this->language_data['STYLES']['REGEXPS'][$this->_rx_key], $matches[1]) . '"' . $matches[1] . '|>';
    }

    /**
     * handles newlines in REGEXPS matches. Set the _hmr_* vars before calling this
     *
     * @note this is a callback, don't use it directly
     *
     * @param array the matches array
     *
     * @return string
     */
    private function handle_multiline_regexps($matches)
    {
        $before = $this->_hmr_before;
        $after = $this->_hmr_after;
        if ($this->_hmr_replace) {
            $replace = $this->_hmr_replace;
            $search = [];

            foreach (array_keys($matches) as $k) {
                $search[] = '\\' . $k;
            }

            $before = str_replace($search, $matches, $before);
            $after = str_replace($search, $matches, $after);
            $replace = str_replace($search, $matches, $replace);
        }
        else {
            $replace = $matches[0];
        }

        return $before
               . '<|!REG3XP' . $this->_hmr_key . '!>'
               . str_replace("\n", "|>\n<|!REG3XP" . $this->_hmr_key . '!>', $replace)
               . '|>'
               . $after;
    }
}
