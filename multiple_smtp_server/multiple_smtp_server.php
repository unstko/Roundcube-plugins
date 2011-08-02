<?php
/**
 * Multiple SMTP server
 *
 * Roundcube plugin to set multiple SMTP server.
 *
 * @version 0.1.1
 * @author Stefan Koch
 * @url https://github.com/unstko/Roundcube-plugins
 * @licence MIT License
 *
 * Copyright (c) 2011 Stefan Koch
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
class multiple_smtp_server extends rcube_plugin
{
    /**
     * @var string Task to run the plugin in.
     */
    public $task = 'mail';

    /**
     * @var boolean Saves if list of hosts is blank.
     */
    public $blank_hosts = false;

    /**
     * Mandatory method to initialize the plugin.
     */
    function init()
    {
        // Load localization
        $this->add_texts('localization/');

        // Link hook for SMTP connection to method smtp_connect
        $this->add_hook('smtp_connect', array($this, 'smtp_connect'));
    }

    /**
     * Callback method for SMTP connection.
     *
     * @param mixed $args Argument containing context-specific data.
     * @return mixed Modified context-specific data.
     */
    function smtp_connect($args)
    {
        // Get rcmail instance
        $rcmail = rcmail::get_instance();

        // Check for task mail
        if ($rcmail->task != "mail") {
            return $args;
        }

        // Load config from a distribution config file
        // and then merge a local configuration file overriding any settings
        $this->load_config('config/config.inc.php.dist');
        $this->load_config('config/config.inc.php');

        // Get config values (global and local)
        $default_host = $rcmail->config->get('default_host', array());
        $multiple_smtp_server = $rcmail->config->get('multiple_smtp_server', array());
        $multiple_smtp_server_message = $rcmail->config->get('multiple_smtp_server_message', false);

        // Check config values
        if (!is_array($default_host) || !is_array($multiple_smtp_server) ||
            !array_count_values($multiple_smtp_server)) {
            return $args;
        }
        if (!array_count_values($default_host)) {
            $this->blank_hosts = true;
        }
        else {
            $this->blank_hosts = false;
        }

        // Get session values
        $imap_host = $_SESSION['imap_host'];
        $imap_port = $_SESSION['imap_port'];
        $imap_ssl = $_SESSION['imap_ssl'];
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];

        // Set SMTP server for current host
        if (!$this->blank_hosts) {
            // Look through array of default hosts
            foreach ($default_host as $host_url => $host_name) {
                // Find right host
                $url = parse_url($host_url);
                $host = $url['host'];
                if (!$host || $host != $imap_host) {
                    continue;
                }

                // Set SMTP server
                foreach ($multiple_smtp_server as $imap_name => $smtp_server) {
                    if ($imap_name != $host_name) {
                        continue;
                    }
                    $args['smtp_server'] = $smtp_server;
                    $args['smtp_user'] = $username;
                    $args['smtp_pass'] = $rcmail->decrypt($password);
                    break 2;
                }
            }
        }
        else {
            // Create complete host name
            $host = "";
            if ($imap_ssl) {
                $host .= "ssl://";
            }
            $host .= $imap_host;
            $host .= ":";
            $host .= $imap_port;

            // Set SMTP server
            foreach ($multiple_smtp_server as $imap_host => $smtp_server) {
                if ($imap_host != $host) {
                    continue;
                }
                $args['smtp_server'] = $smtp_server;
                $args['smtp_user'] = $username;
                $args['smtp_pass'] = $rcmail->decrypt($password);
                break;
            }
        }

        // Show message
        if ($multiple_smtp_server_message) {
            $rcmail->output->show_message("{$this->gettext('smtp_server_message')}: {$args['smtp_server']}" , 'confirmation');
        }

        // Return (modified) arguments
        return $args;
    }
}
?>
