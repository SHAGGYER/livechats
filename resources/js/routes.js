import Home from './pages/home';
import Room from './pages/room';
import Login from './pages/login';
import Register from './pages/register';


const routes = [
    {
        path: '/',
        component: Home,
        name: 'home'
    },

    {
        path: '/room/:id',
        component: Room,
        name: 'room'
    },

    {
        path: '/login',
        component: Login,
        name: 'login'
    },

    {
        path: '/register',
        component: Register,
        name: 'register'
    }
];

export default routes;