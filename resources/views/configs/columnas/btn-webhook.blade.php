<button type="button" class="btn btn-light-primary btnWebhook" {{$model?->estado == 1 ? '' : 'disabled'}} data-clipboard-text="{{url('webhook/'.$model->app_id)}}">
    <i class="fas fa-copy"></i>
</button>