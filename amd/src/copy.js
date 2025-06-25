define(['jquery', 'core/str', 'core/notification', 'core/ajax'],
    function($, str, notification, ajax) {
        return {
            init: function() {
                $('.su-agent-copy').off('click').on('click', function(e) {
                    e.preventDefault();

                    // Function to copy text with fallback mechanism.
                    const copyToClipboard = function(text) {
                        // Modern method using navigator.clipboard.
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            return navigator.clipboard.writeText(text)
                                .then(function() {
                                    return Promise.resolve();
                                })
                                .catch(function(err) {
                                    console.warn('Clipboard API failed, trying fallback:', err);
                                    return copyWithFallback(text);
                                });
                        } else {
                            // Fallback for cases where navigator.clipboard is not available.
                            return copyWithFallback(text);
                        }
                    };

                    // Fallback method using document.execCommand.
                    const copyWithFallback = function(text) {
                        return new Promise(function(resolve, reject) {
                            const textArea = document.createElement("textarea");
                            textArea.value = text;
                            textArea.style.position = "fixed";
                            textArea.style.left = "-999999px";
                            textArea.style.top = "-999999px";
                            document.body.appendChild(textArea);
                            textArea.focus();
                            textArea.select();

                            try {
                                const successful = document.execCommand('copy');
                                document.body.removeChild(textArea);
                                if (successful) {
                                    resolve();
                                } else {
                                    reject(new Error('Copy command failed'));
                                }
                            } catch (err) {
                                document.body.removeChild(textArea);
                                reject(err);
                            }
                        });
                    };

                    // Retrieve language strings and perform copy operation.
                    Promise.all([
                        str.get_string('date', 'block_su_agent_moodle'),
                        str.get_string('server', 'block_su_agent_moodle'),
                        str.get_string('identification', 'block_su_agent_moodle'),
                        str.get_string('configuration', 'block_su_agent_moodle')
                    ]).then(function([dateLabel, serverLabel, identificationLabel, configurationLabel]) {
                        const data = {
                            serverlabel: document.getElementById("serverlabel").querySelector('strong').textContent,
                            identificationlabel: document.getElementById("identificationlabel").querySelector('strong').textContent,
                            ipaddresslabel: document.getElementById("ipaddresslabel").querySelector('strong').textContent,
                            configurationlabel: document.getElementById("configurationlabel").querySelector('i').textContent,
                            datelabel: document.getElementById("datelabel").querySelector('strong').textContent
                        };

                        const clipboardText = dateLabel + " : " + data.datelabel + " | " +
                            serverLabel + " : " + data.serverlabel + " | " +
                            identificationLabel + " : " + data.identificationlabel + " => " +
                            data.ipaddresslabel + " | " +
                            configurationLabel + " : " + data.configurationlabel;

                        return copyToClipboard(clipboardText)
                            .then(function() {
                                return str.get_string('msgalertgood', 'block_su_agent_moodle');
                            })
                            .then(function(successMsg) {
                                alert(successMsg);
                            });
                    }).catch(function(error) {
                        console.error('Copy operation failed:', error);
                        str.get_string('msgalertbadbrowser', 'block_su_agent_moodle')
                            .then(function(errorMsg) {
                                alert(errorMsg);
                            })
                            .fail(notification.exception);
                    });
                });

                $('.su-agent-send').off('click').on('click', function() {
                    const message = $('.su-agent-message').val().trim();

                    if (!message) {
                        str.get_string('msgempty', 'block_su_agent_moodle')
                            .then(function(string) {
                                alert(string);
                            })
                            .fail(notification.exception);
                        return;
                    }

                    const data = {
                        message: message,
                        server: document.getElementById("serverlabel").querySelector('strong').textContent,
                        identification: document.getElementById("identificationlabel").querySelector('strong').textContent,
                        ipaddress: document.getElementById("ipaddresslabel").querySelector('strong').textContent,
                        configuration: document.getElementById("configurationlabel").querySelector('i').textContent,
                        date: document.getElementById("datelabel").querySelector('strong').textContent
                    };

                    ajax.call([{
                        methodname: 'block_su_agent_moodle_send_mail',
                        args: data
                    }])[0].then(function() {
                        str.get_string('msgemailsuccess', 'block_su_agent_moodle')
                            .then(function(successMsg) {
                                notification.addNotification({
                                    message: successMsg,
                                    type: 'success'
                                });
                                $('.su-agent-message').val('');
                            })
                            .fail(notification.exception);
                    }).catch(function() {
                        str.get_string('error', 'block_su_agent_moodle')
                            .then(function(errorMsg) {
                                notification.addNotification({
                                    message: errorMsg,
                                    type: 'error'
                                });
                            })
                            .fail(notification.exception);
                    });
                });
            }
        };
    });
