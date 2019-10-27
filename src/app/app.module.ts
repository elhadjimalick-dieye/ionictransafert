import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { RouteReuseStrategy } from '@angular/router';
import { TokenInterceptorService } from './services/token-interceptor.service';
import { AuthService } from './services/auth.service';
import { IonicModule, IonicRouteStrategy } from '@ionic/angular';
import { SplashScreen } from '@ionic-native/splash-screen/ngx';
import { StatusBar } from '@ionic-native/status-bar/ngx';
import { IonicStorageModule} from '@ionic/storage'
import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';
import { HttpClientModule, HTTP_INTERCEPTORS }    from '@angular/common/http';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import {NgCalendarModule  } from "ionic2-calendar";

@NgModule({
  declarations: [AppComponent],
  entryComponents: [],
  imports: [
    BrowserModule,
    IonicModule.forRoot(),
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    NgCalendarModule
    

  ],
  providers: [
    StatusBar,
    SplashScreen,
    AuthService,

    { provide:HTTP_INTERCEPTORS , useClass: TokenInterceptorService,multi: true }
  ],
  bootstrap: [AppComponent]
})
export class AppModule {}
