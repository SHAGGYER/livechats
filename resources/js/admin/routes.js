import Login from './pages/login';
import Dashboard from './pages/dashboard';
import Profile from './pages/profile';

import ResourceIndex from './pages/resource.index';
import ResourceShow from './pages/resource.show';
import ResourceUpdate from './pages/resource.update';

const routes = [
    {
        path: '/login',
        component: Login,
        name: 'login',
    },

    {
        path: '/',
        component: Dashboard,
        name: 'dashboard'
    },

    {
        path: '/profile',
        component: Profile,
        name: 'profile'
    },

    {
        path: '/resource',
        component: ResourceIndex,
        name: 'resource.index'
    },

    {
        path: '/resource/show',
        component: ResourceShow,
        name: 'resource.show'
    },

    {
        path: '/resource/update',
        component: ResourceUpdate,
        name: 'resource.update'
    }
];

export default routes;