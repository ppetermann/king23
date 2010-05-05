<?php
class EXAMPLE_Static extends King23_TemplateView
{
    protected function index($request)
    {
        $this->render("static/index.html", array());
    }
}
