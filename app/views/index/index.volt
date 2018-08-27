<div class="page-header">
    <h1>Congratulations!</h1>
</div>

{{ content() }}     <!-- これを追加 -->

{{ this.session.destroy() }}

<p>You're now flying with Phalcon. Great things are about to happen!</p>

<p>This page is located at <code>views/index/index.volt</code></p>

<a href="logout" class="btn btn-primary">logout</a>