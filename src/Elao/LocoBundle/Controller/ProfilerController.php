<?php

namespace Elao\LocoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfilerController extends Controller
{
    /**
     * Save the selected translation to resources.
     *
     * @Route("/{token}/translation/save", name="_profiler_save_translations")
     *
     * @return Response A Response instance
     */
    public function saveAction(Request $request, $token)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('_profiler', ['token' => $token]);
        }

        $profiler = $this->get('profiler');
        $profiler->disable();

        $selected   = $request->request->get('selected');
        if (!$selected || count($selected) == 0) {
            return new Response('No key selected.');
        }

        $profile    = $profiler->loadProfile($token);
        $all        = $profile->getCollector('translation');
        $toSave     = array_intersect_key($all->getMessages(), array_flip($selected));

        try {
            $this->get('loco.handler')->create($toSave);
        } catch (\Exception $e) {
            return new Response("Can't save the translations. Reason was : " . $e->getMessage());
        }

        $this->get('loco.handler')->download();

        return new Response(sprintf('%s translation keys saved ! Translation files have been updated.', count($selected)));
    }
}
