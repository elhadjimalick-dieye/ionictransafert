import { Injectable } from '@angular/core';
import { ToastController } from '@ionic/angular';

@Injectable({
  providedIn: 'root'
})
export class AlertService {

  constructor(private toastController: ToastController) { }
  async presentToast(message: any) {
    const toast = await this.toastController.create({
      message: message,
      duration: 2000,
      position: 'top',
      color: 'dark'
    });
    toast.present();
  }
  /**
   * ToastController Ce plugin vous permet d'afficher un Toast natif (un petit texte contextuel) 
   * sur iOS, Android et WP8. C'est excellent pour afficher 
   * une notification native non intrusive qui est toujours 
   * garantie dans la fenêtre du navigateur.
   */


   
}
