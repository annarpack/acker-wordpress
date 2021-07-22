<?php

namespace AckerWines;


trait UserTraits
{
    function getApcId() {
        $apcid = NULL;

        try {
            $apcid = get_user_meta(get_current_user_id(), 'aw_apcid', true);
        } catch (\Exception $e) {
            aw_logMessage($e->getMessage());
        }

        return $apcid;
    }
}
