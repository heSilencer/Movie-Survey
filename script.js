$(document).ready(function () {
    // Handle radio button change event for all elements with class "inputRadio"
    $('.inputRadio').on('change', function () {
        // Get the corresponding label and text input based on the radio button's ID
        var labelId = '#' + $(this).attr('id').replace('Radio', 'Label');
        var specifyId = '#' + $(this).attr('id').replace('Radio', 'Specify');

        if ($(this).val() === 'other') {
            // If "Other" is selected and the text input has no value, show the label and text input
            if ($(specifyId).val() === '') {
                $(labelId).show();
            }
        } else {
            // If any other option is selected, hide both label and text input
            $(labelId).hide();
        }
    });

    // Handle text input change event for all elements with class "inputText"
    $('.inputText').on('input', function () {
        // Get the corresponding label based on the text input's ID
        var labelId = '#' + $(this).attr('id').replace('Specify', 'Label');

        // If there is a value in the text input, hide the label
        if ($(this).val() !== '') {
            $(labelId).hide();
        }
    });
    $('input[name="device"]').on('change', function() {
        if ($(this).val() === 'other') {
            // If "Other" is selected, show the label and text input
            $('#otherDeviceLabel').show();
        } else {
            // If any other option is selected, hide the label and text input
            $('#otherDeviceLabel').hide();
        }
    });
    $('input[name="avoid"]').on('change', function() {
        if ($(this).val() === 'other') {
            // If "Other" is selected, show the label and text input
            $('#otherAvoidLabel').show();
        } else {
            // If any other option is selected, hide the label and text input
            $('#otherAvoidLabel').hide();
        }
    });
    $('input[name="watching"]').on('change', function() {
        if ($(this).val() === 'other') {
            // If "Other" is selected, show the label and text input
            $('#otherWatchingLabel').show();
        } else {
            // If any other option is selected, hide the label and text input
            $('#otherWatchingLabel').hide();
        }
    });
});