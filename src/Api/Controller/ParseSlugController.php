<?php
namespace Avatar4eg\Transliterator\Api\Controller;

use Flarum\Core\Access\AssertPermissionTrait;
use Flarum\Core\Repository\DiscussionRepository;
use Flarum\Http\Controller\ControllerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Zend\Diactoros\Response\JsonResponse;
use Illuminate\Contracts\Bus\Dispatcher;
use Flarum\Core\Command\EditDiscussion;
use Behat\Transliterator\Transliterator;

class ParseSlugController implements ControllerInterface
{
    use AssertPermissionTrait;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var DiscussionRepository
     */
    protected $discussions;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param TranslatorInterface $translator
     * @param DiscussionRepository $discussions
     * @param Dispatcher $bus
     */
    public function __construct(TranslatorInterface $translator, DiscussionRepository $discussions, Dispatcher $bus)
    {
        $this->translator = $translator;
        $this->discussions = $discussions;
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function handle(ServerRequestInterface $request)
    {
        $actor = $request->getAttribute('actor');
        $result = false;
        $counter = 0;

        if ($actor !== null && $actor->isAdmin() && $request->getMethod() === 'POST') {
            $discussions = $this->discussions->query()->whereVisibleTo($actor)->get();
            foreach ($discussions as $discussion) {
                $slug = Transliterator::transliterate($discussion->title);
                if ($discussion->slug !== $slug) {
                    $data = [
                        'type' => 'discussions',
                        'id' => $discussion->id,
                        'attributes' => [
                            'title' => $discussion->title
                        ]
                    ];
                    $this->bus->dispatch(
                        new EditDiscussion($discussion->id, $actor, $data)
                    );
                    $counter++;
                }
            }
            $result = true;
        }

        $string = $this->translator->trans('avatar4eg-transliterator.admin.settings.result');
        return new JsonResponse([
            'success'   => $result,
            'message'   => "$string $counter"
        ]);
    }
}
