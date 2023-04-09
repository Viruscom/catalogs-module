<!-- Модал за добавяне на снимки -->
<div class="modal fade" id="{{$addModalId}}" tabindex="-1" role="dialog" aria-labelledby="addImagesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="{{$formId}}" data-action-url="{{ route('admin.gallery.store-multiple-images') }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="addImagesModalLabel" style="float: left;">{{ __('admin.gallery.add_images_header') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('admin.gallery.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="success-message" class="myadmin-alert alert alert-success notify-position-top d-flex" style="display: none; justify-content: space-between;">
                        <div>
                            <i class="fas fa-check m-r-5"></i>
                            <span></span>
                        </div>
                        <a href="#" class="closed"><i class="far fa-times-circle"></i></a>
                    </div>
                    <div id="error-message" class="myadmin-alert alert alert-danger notify-position-top d-flex" style="display: none; justify-content: space-between;">
                        <div>
                            <i class="fas fa-bug"></i>
                            <span></span>
                        </div>
                        <a href="#" class="closed"><i class="far fa-times-circle"></i></a>
                    </div>

                    <input type="hidden" name="type" value="{{$galleryType}}">
                    <input type="hidden" name="module" value="{{$moduleName}}">
                    <input type="hidden" name="model" value="{{$modelPath}}">
                    <input type="hidden" name="model_id" value="{{$model->id}}">
                    @csrf
                    <ul class="nav nav-tabs">
                        @foreach($languages as $language)
                            <li @if($language->code === config('default.app.language.code')) class="active" @endif><a data-toggle="tab" href="#{{$language->code}}">{{$language->code}} <span class="err-span-{{$language->code}} hidden text-purple"><i class="fas fa-exclamation"></i></span></a></li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach($languages as $language)
                                <?php $langTitle = 'title_' . $language->code; ?>
                            <div class="form-group @if($errors->has($langTitle)) has-error @endif">
                                <label for="{{$langTitle}}" class="control-label p-b-10">
                                    <span class="text-purple">* </span>
                                    {{trans('admin.title')}} (<span class="text-uppercase">{{$language->code}}</span>):
                                </label>
                                <input id="{{$langTitle}}" class="form-control" type="text" name="{{$langTitle}}" value="{{ old($langTitle)?: $model->title }}">
                                @if($errors->has($langTitle))
                                    <span class="help-block">{{ trans($errors->first($langTitle)) }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label p-0 col-md-12">{{ __('admin.active_visible_on_site') }}:</label>
                                <div class="col-md-12 p-0">
                                    <label class="switch pull-left">
                                        <input type="checkbox" name="active" class="success" data-size="small" checked {{old('active') ? 'checked' : 'active'}}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label p-0 col-md-12">{{ __('admin.gallery.images') }}</label>
                                <div class="col-md-12 p-0">
                                    <input type="file" name="images[]" multiple class="filestyle" data-buttonText="{{ trans('admin.browse_file') }}" data-iconName="fas fa-upload" data-buttonName="btn green" data-badge="true">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey-eye" data-dismiss="modal">{{ __('admin.gallery.close') }}</button>
                    <button type="submit" class="btn green">{{ __('admin.gallery.add_images_btn') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const addImagesForm = document.getElementById("{{$formId}}");

        addImagesForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData  = new FormData(addImagesForm);
            const actionUrl = addImagesForm.getAttribute('data-action-url');

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData,
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        const tableBody = document.querySelector('.table-{{$formId}} tbody');
                        tableBody.innerHTML = response.view_page;
                        $('#error-message').hide();
                        $('#success-message span').text(response.success);
                        $('#success-message').show();
                        setTimeout(function () {
                            $('#success-message').hide();
                            $('#{{$addModalId}}').modal('hide');
                        }, 1000);
                    } else {
                        displayErrors(response.error, response.invalid_images);

                    }
                })
                .catch((jqXHR, textStatus, errorThrown) => {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                    const errorMessageText = 'Възникна грешка при добавянето на снимки.';
                    $('#error-message span').text(errorMessageText);
                    $('#success-message').hide();
                    $('#error-message').show();
                });
        });

        function displayErrors(errorMsg, invalidImages) {
            const errorSpan = document.querySelector("#error-message span");
            const errorMessageText = errorMsg || 'Възникна грешка при добавянето на снимки.';
            errorSpan.innerHTML = '<span>'+ errorMessageText + '</span><hr style="margin-top: 10px;margin-bottom: 10px;">';

            let errorList = document.createElement("ul");
            errorList.style.paddingLeft = "5px";
            for (let i = 0; i < invalidImages.length; i++) {
                let invalidImage = invalidImages[i];
                let imageTitle = document.createElement("li");
                imageTitle.innerHTML = '<strong>' + invalidImage.original_name + ':</strong>';
                imageTitle.style.listStyle = "none";
                errorList.appendChild(imageTitle);

                for (let j = 0; j < invalidImage.errors.length; j++) {
                    let errorItem = document.createElement("li");
                    errorItem.style.marginLeft = "13px";
                    errorItem.innerText = invalidImage.errors[j];
                    errorList.appendChild(errorItem);
                }
            }
            errorSpan.appendChild(errorList);

            $('#success-message').hide();
            $('#error-message').show();
        }


    });
</script>
