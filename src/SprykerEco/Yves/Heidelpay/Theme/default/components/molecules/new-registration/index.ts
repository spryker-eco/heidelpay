import './new-registration.scss';
import register from 'ShopUi/app/registry';
export default register('new-registration', () => import(
    /* webpackMode: "lazy" */
    /* webpackChunkName: "new-registration" */
    './new-registration'));
