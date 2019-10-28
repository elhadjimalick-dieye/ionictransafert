import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  {
    path: '',
    redirectTo: 'login',
    pathMatch: 'full'
  }, 
  {
    path: 'home',
    loadChildren: () => import('./home/home.module').then(m => m.HomePageModule)
  },

  { path: 'login',
   loadChildren: () => import ('./pages/auth/login/login.module') .then (m => m.LoginPageModule)
  },
  { path: 'register', 
  loadChildren: () => import ('./pages/auth/register/register.module') .then (m => m.RegisterPageModule) 
  },
  { path: 'transaction', loadChildren: './transaction/transaction.module#TransactionPageModule' },
  { path: 'transactionliste', loadChildren: './transactionliste/transactionliste.module#TransactionlistePageModule' },
  { path: 'menu', loadChildren: './pages/menu/menu.module#MenuPageModule' },

];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule {}
