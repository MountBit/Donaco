import $ from 'jquery';

$(document).ready(function() {
    const $messageHiddenCheckbox = $('#message_hidden');
    const $messageHiddenReasonContainer = $('#message_hidden_reason_container');
    const $messageHiddenReasonSelect = $('#message_hidden_reason');
    const $reasonError = $('#reason_error');

    function toggleMessageHiddenReason() {
        if ($messageHiddenCheckbox.is(':checked')) {
            $messageHiddenReasonContainer.slideDown(300);
            $messageHiddenReasonSelect.focus();
            validateReason();
        } else {
            $messageHiddenReasonContainer.slideUp(300);
            $messageHiddenReasonSelect.val('');
            $reasonError.hide();
        }
    }

    function validateReason() {
        if ($messageHiddenCheckbox.is(':checked') && !$messageHiddenReasonSelect.val()) {
            $reasonError.slideDown(300);
            return false;
        }
        $reasonError.slideUp(300);
        return true;
    }

    // Event Listeners
    $messageHiddenCheckbox.on('change', toggleMessageHiddenReason);
    $messageHiddenReasonSelect.on('change', validateReason);

    // Form submission
    $('form').on('submit', function(e) {
        if ($messageHiddenCheckbox.is(':checked') && !validateReason()) {
            e.preventDefault();
            $messageHiddenReasonSelect.focus();
        }
    });

    // Initial state
    if ($messageHiddenCheckbox.is(':checked')) {
        $messageHiddenReasonContainer.show();
        validateReason();
    } else {
        $messageHiddenReasonContainer.hide();
    }
});
