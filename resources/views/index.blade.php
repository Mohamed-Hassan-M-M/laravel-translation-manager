@extends('layouts.admin.app')

@section('title')
@lang('translations.translations')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('admin-assets/custom/bootstrap4-editable/css/bootstrap-editable.css')}}" />
    <style>
        a.status-1{
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">@lang('general.dashboard')</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{url('admin/translations')}}">@lang('translations.translations')</a>
                                </li>
                                <li class="breadcrumb-item active"> @lang('translations.import')
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-6 col-12">
                    <div class="btn-group">
                        <a href="{{ url()->previous() }}" class="btn btn-round btn-info mb-1 buttonAnimation" data-animation="flash" > <span class="display-inline-block">@lang('general.back')</span> </a>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Form Section -->
                <section id="horizontal-form-layouts">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">@lang('translations.import')</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collpase show">
                                    <div class="card-body">
                                        <div>
                                            <div class="alert alert-success success-import" style="display:none;">
                                                <p>Done importing, processed <strong class="counter">N</strong> items! Reload this page to refresh the groups!</p>
                                            </div>
                                            <div class="alert alert-success success-find" style="display:none;">
                                                <p>Done searching for translations, found <strong class="counter">N</strong> items!</p>
                                            </div>
                                            <div class="alert alert-success success-publish" style="display:none;">
                                                <p>Done publishing the translations for group '<?php echo $group ?>'!</p>
                                            </div>
                                            <div class="alert alert-success success-publish-all" style="display:none;">
                                                <p>Done publishing the translations for all group!</p>
                                            </div>
                                            <?php if(Session::has('successPublish')) : ?>
                                            <div class="alert alert-info">
                                                <?php echo Session::get('successPublish'); ?>
                                            </div>
                                            <?php endif; ?>
                                            <p>
                                            <?php if(!isset($group)) : ?>
                                            <form class="form-find" method="POST" action="<?php echo action('\Acmetemplate\TranslationManager\Controller@postFind') ?>" data-remote="true" role="form" data-confirm="Are you sure you want to scan you app folder? All found translation keys will be added to the database.">
                                                <div class="form-group">
                                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                    <button type="submit" class="btn btn-info" data-disable-with="Searching.." >Find translations in files</button>
                                                </div>
                                            </form>
                                            <form class="form-import" method="POST" action="<?php echo action('\Acmetemplate\TranslationManager\Controller@postImport') ?>" data-remote="true" role="form">
                                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <select name="replace" class="form-control">
                                                                <option value="0">Append new translations</option>
                                                                <option value="1">Replace existing translations</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <button type="submit" class="btn btn-success btn-block"  data-disable-with="Loading..">Import groups</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <hr>
                                            <div class="row mt-2 mb-3">
                                                <p class="col-sm-12">Locales</p>
                                                <div class="col-sm-8">
                                                    <select class="form-control">
                                                        @foreach($locales as $locale)
                                                            <option value="">{{$locale}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <a href="{{route('view.locale')}}" class="btn btn-secondary btn-block"  data-disable-with="Loading..">Manage Locales</a>
                                                </div>
                                            </div>

                                            <hr>
                                            <?php endif; ?>
                                            <?php if(isset($group)) : ?>
                                            <form class="form-inline form-publish" method="POST" action="<?php echo action('\Acmetemplate\TranslationManager\Controller@postPublish', $group) ?>" data-remote="true" role="form" data-confirm="Are you sure you want to publish the translations group '<?php echo $group ?>? This will overwrite existing language files.">
                                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                <button type="submit" class="btn btn-info" data-disable-with="Publishing.." >Publish translations</button>
                                                <a href="<?= action('\Acmetemplate\TranslationManager\Controller@getIndex') ?>" class="btn btn-default">Back</a>
                                            </form>
                                            <?php endif; ?>
                                            </p>
                                            <form role="form" method="POST" action="<?php echo action('\Acmetemplate\TranslationManager\Controller@postAddGroup') ?>">
                                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                <div class="form-group">
                                                    <p>@lang('translations.groupName')</p>
                                                    <select name="group" id="group" class="form-control group-select">
                                                        <?php foreach($groups as $key => $value): ?>
                                                        <option value="<?php echo $key ?>"<?php echo $key == $group ? ' selected':'' ?>><?php echo $value ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-8">
                                                        <input type="text" class="form-control" placeholder="Add or Edit group" name="new-group" />
                                                    </div>
                                                    <div class="form-group col-4">
                                                        <input type="submit" class="btn w-100 btn-primary" name="add-group" value="Add and edit keys" />
                                                    </div>
                                                </div>

                                            </form>
                                            <?php if($group): ?>
                                            <form action="<?php echo action('\Acmetemplate\TranslationManager\Controller@postAdd', array($group)) ?>" method="POST"  role="form">
                                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                <div class="form-group">
                                                    <label>Add new keys to this group</label>
                                                    <textarea class="form-control" rows="3" name="keys" placeholder="Add 1 key per line, without the group prefix"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit" value="Add keys" class="btn btn-primary">
                                                </div>
                                            </form>
                                            <hr>
                                            <h4>Total: <?= $numTranslations ?>, changed: <?= $numChanged ?></h4>
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th width="15%">Key</th>
                                                    <?php foreach ($locales as $locale): ?>
                                                    <th><?= $locale ?></th>
                                                    <?php endforeach; ?>
                                                    <?php if ($deleteEnabled): ?>
                                                    <th>&nbsp;</th>
                                                    <?php endif; ?>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php foreach ($translations as $key => $translation): ?>
                                                <tr id="<?php echo htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?>">
                                                    <td><?php echo htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?></td>
                                                    <?php foreach ($locales as $locale): ?>
                                                    <?php $t = isset($translation[$locale]) ? $translation[$locale] : null ?>

                                                    <td>
                                                        <a href="#edit"
                                                           class="editable status-<?php echo $t ? $t->status : 0 ?> locale-<?php echo $locale ?>"
                                                           data-locale="<?php echo $locale ?>" data-name="<?php echo $locale . "|" . htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?>"
                                                           id="username" data-type="textarea" data-pk="<?php echo $t ? $t->id : 0 ?>"
                                                           data-url="<?php echo $editUrl ?>"
                                                           data-title="Enter translation"><?php echo $t ? htmlentities($t->value, ENT_QUOTES, 'UTF-8', false) : '' ?></a>
                                                    </td>
                                                    <?php endforeach; ?>
                                                    <?php if ($deleteEnabled): ?>
                                                    <td>
                                                        <a href="<?php echo action('\Acmetemplate\TranslationManager\Controller@postDelete', [$group, $key]) ?>"
                                                           class="delete-key"
                                                           data-confirm="Are you sure you want to delete the translations for '<?php echo htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?>?"><span
                                                                class="la la-lg la-trash"></span></a>
                                                    </td>
                                                    <?php endif; ?>
                                                </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <?php else: ?>
                                            <fieldset class="mt-2">
                                                <legend>Export all translations</legend>
                                                <form class="form-inline form-publish-all" method="POST" action="<?php echo action('\Acmetemplate\TranslationManager\Controller@postPublish', '*') ?>" data-remote="true" role="form" data-confirm="Are you sure you want to publish all translations group? This will overwrite existing language files.">
                                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                    <button type="submit" class="btn btn-primary" data-disable-with="Publishing.." >Publish all</button>
                                                </form>
                                            </fieldset>

                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Form Sections -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('admin-assets/custom/bootstrap4-editable/js/bootstrap-editable.min.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
    <script>//https://github.com/rails/jquery-ujs/blob/master/src/rails.js
        (function(e,t){if(e.rails!==t){e.error("jquery-ujs has already been loaded!")}var n;var r=e(document);e.rails=n={linkClickSelector:"a[data-confirm], a[data-method], a[data-remote], a[data-disable-with]",buttonClickSelector:"button[data-remote], button[data-confirm]",inputChangeSelector:"select[data-remote], input[data-remote], textarea[data-remote]",formSubmitSelector:"form",formInputClickSelector:"form input[type=submit], form input[type=image], form button[type=submit], form button:not([type])",disableSelector:"input[data-disable-with], button[data-disable-with], textarea[data-disable-with]",enableSelector:"input[data-disable-with]:disabled, button[data-disable-with]:disabled, textarea[data-disable-with]:disabled",requiredInputSelector:"input[name][required]:not([disabled]),textarea[name][required]:not([disabled])",fileInputSelector:"input[type=file]",linkDisableSelector:"a[data-disable-with]",buttonDisableSelector:"button[data-remote][data-disable-with]",CSRFProtection:function(t){var n=e('meta[name="csrf-token"]').attr("content");if(n)t.setRequestHeader("X-CSRF-Token",n)},refreshCSRFTokens:function(){var t=e("meta[name=csrf-token]").attr("content");var n=e("meta[name=csrf-param]").attr("content");e('form input[name="'+n+'"]').val(t)},fire:function(t,n,r){var i=e.Event(n);t.trigger(i,r);return i.result!==false},confirm:function(e){return confirm(e)},ajax:function(t){return e.ajax(t)},href:function(e){return e.attr("href")},handleRemote:function(r){var i,s,o,u,a,f,l,c;if(n.fire(r,"ajax:before")){u=r.data("cross-domain");a=u===t?null:u;f=r.data("with-credentials")||null;l=r.data("type")||e.ajaxSettings&&e.ajaxSettings.dataType;if(r.is("form")){i=r.attr("method");s=r.attr("action");o=r.serializeArray();var h=r.data("ujs:submit-button");if(h){o.push(h);r.data("ujs:submit-button",null)}}else if(r.is(n.inputChangeSelector)){i=r.data("method");s=r.data("url");o=r.serialize();if(r.data("params"))o=o+"&"+r.data("params")}else if(r.is(n.buttonClickSelector)){i=r.data("method")||"get";s=r.data("url");o=r.serialize();if(r.data("params"))o=o+"&"+r.data("params")}else{i=r.data("method");s=n.href(r);o=r.data("params")||null}c={type:i||"GET",data:o,dataType:l,beforeSend:function(e,i){if(i.dataType===t){e.setRequestHeader("accept","*/*;q=0.5, "+i.accepts.script)}if(n.fire(r,"ajax:beforeSend",[e,i])){r.trigger("ajax:send",e)}else{return false}},success:function(e,t,n){r.trigger("ajax:success",[e,t,n])},complete:function(e,t){r.trigger("ajax:complete",[e,t])},error:function(e,t,n){r.trigger("ajax:error",[e,t,n])},crossDomain:a};if(f){c.xhrFields={withCredentials:f}}if(s){c.url=s}return n.ajax(c)}else{return false}},handleMethod:function(r){var i=n.href(r),s=r.data("method"),o=r.attr("target"),u=e("meta[name=csrf-token]").attr("content"),a=e("meta[name=csrf-param]").attr("content"),f=e('<form method="post" action="'+i+'"></form>'),l='<input name="_method" value="'+s+'" type="hidden" />';if(a!==t&&u!==t){l+='<input name="'+a+'" value="'+u+'" type="hidden" />'}if(o){f.attr("target",o)}f.hide().append(l).appendTo("body");f.submit()},formElements:function(t,n){return t.is("form")?e(t[0].elements).filter(n):t.find(n)},disableFormElements:function(t){n.formElements(t,n.disableSelector).each(function(){n.disableFormElement(e(this))})},disableFormElement:function(e){var t=e.is("button")?"html":"val";e.data("ujs:enable-with",e[t]());e[t](e.data("disable-with"));e.prop("disabled",true)},enableFormElements:function(t){n.formElements(t,n.enableSelector).each(function(){n.enableFormElement(e(this))})},enableFormElement:function(e){var t=e.is("button")?"html":"val";if(e.data("ujs:enable-with"))e[t](e.data("ujs:enable-with"));e.prop("disabled",false)},allowAction:function(e){var t=e.data("confirm"),r=false,i;if(!t){return true}if(n.fire(e,"confirm")){r=n.confirm(t);i=n.fire(e,"confirm:complete",[r])}return r&&i},blankInputs:function(t,n,r){var i=e(),s,o,u=n||"input,textarea",a=t.find(u);a.each(function(){s=e(this);o=s.is("input[type=checkbox],input[type=radio]")?s.is(":checked"):s.val();if(!o===!r){if(s.is("input[type=radio]")&&a.filter('input[type=radio]:checked[name="'+s.attr("name")+'"]').length){return true}i=i.add(s)}});return i.length?i:false},nonBlankInputs:function(e,t){return n.blankInputs(e,t,true)},stopEverything:function(t){e(t.target).trigger("ujs:everythingStopped");t.stopImmediatePropagation();return false},disableElement:function(e){e.data("ujs:enable-with",e.html());e.html(e.data("disable-with"));e.bind("click.railsDisable",function(e){return n.stopEverything(e)})},enableElement:function(e){if(e.data("ujs:enable-with")!==t){e.html(e.data("ujs:enable-with"));e.removeData("ujs:enable-with")}e.unbind("click.railsDisable")}};if(n.fire(r,"rails:attachBindings")){e.ajaxPrefilter(function(e,t,r){if(!e.crossDomain){n.CSRFProtection(r)}});r.delegate(n.linkDisableSelector,"ajax:complete",function(){n.enableElement(e(this))});r.delegate(n.buttonDisableSelector,"ajax:complete",function(){n.enableFormElement(e(this))});r.delegate(n.linkClickSelector,"click.rails",function(r){var i=e(this),s=i.data("method"),o=i.data("params"),u=r.metaKey||r.ctrlKey;if(!n.allowAction(i))return n.stopEverything(r);if(!u&&i.is(n.linkDisableSelector))n.disableElement(i);if(i.data("remote")!==t){if(u&&(!s||s==="GET")&&!o){return true}var a=n.handleRemote(i);if(a===false){n.enableElement(i)}else{a.error(function(){n.enableElement(i)})}return false}else if(i.data("method")){n.handleMethod(i);return false}});r.delegate(n.buttonClickSelector,"click.rails",function(t){var r=e(this);if(!n.allowAction(r))return n.stopEverything(t);if(r.is(n.buttonDisableSelector))n.disableFormElement(r);var i=n.handleRemote(r);if(i===false){n.enableFormElement(r)}else{i.error(function(){n.enableFormElement(r)})}return false});r.delegate(n.inputChangeSelector,"change.rails",function(t){var r=e(this);if(!n.allowAction(r))return n.stopEverything(t);n.handleRemote(r);return false});r.delegate(n.formSubmitSelector,"submit.rails",function(r){var i=e(this),s=i.data("remote")!==t,o,u;if(!n.allowAction(i))return n.stopEverything(r);if(i.attr("novalidate")==t){o=n.blankInputs(i,n.requiredInputSelector);if(o&&n.fire(i,"ajax:aborted:required",[o])){return n.stopEverything(r)}}if(s){u=n.nonBlankInputs(i,n.fileInputSelector);if(u){setTimeout(function(){n.disableFormElements(i)},13);var a=n.fire(i,"ajax:aborted:file",[u]);if(!a){setTimeout(function(){n.enableFormElements(i)},13)}return a}n.handleRemote(i);return false}else{setTimeout(function(){n.disableFormElements(i)},13)}});r.delegate(n.formInputClickSelector,"click.rails",function(t){var r=e(this);if(!n.allowAction(r))return n.stopEverything(t);var i=r.attr("name"),s=i?{name:i,value:r.val()}:null;r.closest("form").data("ujs:submit-button",s)});r.delegate(n.formSubmitSelector,"ajax:send.rails",function(t){if(this==t.target)n.disableFormElements(e(this))});r.delegate(n.formSubmitSelector,"ajax:complete.rails",function(t){if(this==t.target)n.enableFormElements(e(this))});e(function(){n.refreshCSRFTokens()})}})(jQuery)
    </script>
    <script>
        jQuery(document).ready(function($){

            $.ajaxSetup({
                beforeSend: function(xhr, settings) {
                    console.log('beforesend');
                    settings.data += "&_token=<?php echo csrf_token() ?>";
                }
            });

            $('.editable').editable().on('hidden', function(e, reason){
                var locale = $(this).data('locale');
                if(reason === 'save'){
                    $(this).removeClass('status-0').addClass('status-1');
                }
                if(reason === 'save' || reason === 'nochange') {
                    var $next = $(this).closest('tr').next().find('.editable.locale-'+locale);
                    setTimeout(function() {
                        $next.editable('show');
                    }, 300);
                }
            });

            $('.group-select').on('change', function(){
                var group = $(this).val();
                if (group) {
                    window.location.href = '<?php echo action('\Acmetemplate\TranslationManager\Controller@getView') ?>/'+$(this).val();
                } else {
                    window.location.href = '<?php echo action('\Acmetemplate\TranslationManager\Controller@getIndex') ?>';
                }
            });

            $("a.delete-key").on('confirm:complete',function(event,result){
                if(result)
                {
                    var row = $(this).closest('tr');
                    var url = $(this).attr('href');
                    var id = row.attr('id');
                    $.post( url, {id: id}, function(){
                        row.remove();
                    } );
                }
                return false;
            });

            $('.form-import').on('ajax:success', function (e, data) {
                $('div.success-import strong.counter').text(data.counter);
                $('div.success-import').slideDown();
                window.location.reload();
            });

            $('.form-find').on('ajax:success', function (e, data) {
                $('div.success-find strong.counter').text(data.counter);
                $('div.success-find').slideDown();
                window.location.reload();
            });

            $('.form-publish').on('ajax:success', function (e, data) {
                $('div.success-publish').slideDown();
            });

            $('.form-publish-all').on('ajax:success', function (e, data) {
                $('div.success-publish-all').slideDown();
            });
            $('.enable-auto-translate-group').click(function (event) {
                event.preventDefault();
                $('.autotranslate-block-group').removeClass('hidden');
                $('.enable-auto-translate-group').addClass('hidden');
            })
            $('#base-locale').change(function (event) {
                console.log($(this).val());
                $.cookie('base_locale', $(this).val());
            })
            if (typeof $.cookie('base_locale') !== 'undefined') {
                $('#base-locale').val($.cookie('base_locale'));
            }

        })
    </script>
@endpush
