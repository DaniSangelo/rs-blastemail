<?php

use App\Models\EmailTemplate;

use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;

it('should be able to delete a template from a list', function () {
    login();

    $template = EmailTemplate::factory()->create();

    delete(route('email-template.destroy', ['email_template' => $template]))
        ->assertRedirectToRoute('email-template.index')
        ->assertSessionHas('message', __('Template deleted successfully'));

    assertSoftDeleted('email_templates', [
        'id' => $template->id,
    ]);
});