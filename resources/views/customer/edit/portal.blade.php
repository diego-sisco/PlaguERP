<div class="row">
    <div class="col-12 mb-3">
        <label for="urlp" class="form-label">{{ __('customer.data.url') }}: </label>
        <input type="text" class="form-control border-secondary border-opacity-50" id="url" name="url"
            value="{{ $customer->url }}">
    </div>
    <div class="col-4 mb-3">
        <label for="userp" class="form-label">{{ __('customer.data.username') }} : </label>
        <input type="text" class="form-control border-secondary border-opacity-50" id="username" name="username"
            value="{{ $customer->username }}">
    </div>
    <div class="col-4 mb-3">
        <label for="emailp" class="form-label">{{ __('customer.data.web_email') }} :</label>
        <input type="text" class="form-control border-secondary border-opacity-50" id="portal_email"
            name="portal_email" value="{{ $customer->portal_email }}">
    </div>
    <div class="col-4 mb-3">
        <label for="passp" class="form-label">{{ __('customer.data.web_password') }} : </label>
        <input type="text" class="form-control border-secondary border-opacity-50" id="password" name="password"
            value="{{ $customer->password }}">
    </div>
</div>
