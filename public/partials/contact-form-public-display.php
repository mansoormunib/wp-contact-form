<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @package    Contact_Form
 * @subpackage Contact_Form/public/partials
 */
?>
<div class="wrap contact_form_wrap">

    <div class="contact_form_row">
        <div class="contact_form_message"></div>
        <form name="contact_form_block" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'/?plugin_page=contactform'; ?>" method="post">
             <div class="contact_form_col1">
                <div class="input_name">
                    <input type="text" name="name-sscf" pattern=".{2,}" required title="minimum 2 characters" placeholder="Name: *"  value="">
                </div>
                <div class="input_email">
                    <input type="email" name="email-sscf" required placeholder="Email: *"  value="">
                </div>
            </div>
            <div class="contact_form_col2">
                <textarea name="description-sscf" class="textarea medium" placeholder="Description:" rows="5" cols="50"></textarea>
            </div>
            <div class="contact_form_submit">
                <input type="submit" />
            </div>
        </form><!-- end form -->
    </div>

</div>
