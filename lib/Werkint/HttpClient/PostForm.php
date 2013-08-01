<?php
namespace Werkint\HttpClient;

class PostForm
{
    protected $post;
    protected $url;

    public function __construct(
        $url,
        array $post
    ) {
        $this->url = $url;
        $this->post = $post;
    }

    public function getForm()
    {
        $id = 'form_' . sha1(microtime(true) . 'form');
        $target = $this->target ? $this->target : '_self';
        $ret = sprintf(
            '<form method="POST" action="%s" id="%s" target="%s">',
            $this->url, $id, $target
        );
        $rowTpl = '<input type="hidden" name="%s" value="%s" />';
        foreach ($this->post as $name => $value) {
            $ret .= sprintf($rowTpl, htmlspecialchars($name), htmlspecialchars($value));
        }
        if ($this->target == '_blank') {
            $ret .= '<button type="submit">Перейти к оплате</button>';
        }
        $ret .= '</form>';

        return $ret;
    }

    /**
     * Целевое окно формы
     * @var null|string
     */
    protected $target = null;

    /**
     * @param null|string $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTarget()
    {
        return $this->target;
    }
}
