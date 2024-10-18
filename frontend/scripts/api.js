// API handler for backend... Also works in 3rd party popup.

import { API_URL } from "./constants.js"

async function getModelsForApp(appId, appKey) {
    // use fetch api
    const response = await fetch(`${API_URL}/api.php?app_id=${appId}&app_key=${appKey}&fn=getModelsForApp`);

    // check response
    if (response.ok) {
        return await response.json();
    }

    // otherwise empty result
    return [];
}

export {
    getModelsForApp
};