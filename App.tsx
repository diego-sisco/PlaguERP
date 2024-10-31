import React from 'react';
import { Navigation } from 'react-native-navigation';
import Icon from 'react-native-vector-icons/EvilIcons';
import { Home } from './app/screens/Home';
import { Details } from './app/screens/Details';
import { QRScanner } from './app/screens/QRScanner';
import { DeviceCheck } from './app/screens/DeviceCheck';

import { Login } from './app/screens/Login';
import { Order } from './app/screens/Order';
import { Device } from './app/screens/Device';
import { Service } from './app/screens/Service';

interface HomeScreenProps {
  componentId: string;
}

export const LoginScreen = (props: any) => {
  return <Login viewId={props.componentId} viewName={'Home'} />;
};

export const OrderScreen = (props: any) => {
  return (
    <Order orderID={props.orderID} redirected={props.redirected} viewId={props.componentId} viewService={'Service'} viewDevice={'Device'}/>
  );
};

export const DeviceScreen = (props: any) => {
  return (
    <Device orderID={props.orderID} redirected={props.redirected} viewId={props.componentId} viewDetails={'Details'} viewQRScanner={'QRScanner'} viewDeviceCheck={'DeviceCheck'}/>
  );
};

export const ServiceScreen = (props: any) => {
  return (
    <Service orderID={props.orderID} redirected={props.redirected} viewId={props.componentId} viewDetails={'Details'} />
  );
};

export const DetailsScreen = (props: any) => {
  return (
    <Details data={props.data} orderID={props.orderID} viewId={props.componentId} viewName={'Home'} />
  );
};

export const QRScannerScreen = (props: any) => {
  return (
    <QRScanner redirectID={props.redirectID} orderID={props.orderID} viewId={props.componentId} viewRedirect={'DeviceCheck'}/>
  );
}

export const DeviceCheckScreen = (props: any) => {
  return (
    <DeviceCheck redirectID={props.redirectID} orderID={props.orderID} deviceID={props.deviceID} serviceID={props.serviceID} isScanned={props.isScanned} viewId={props.componentId} viewDevice={'Device'}/>
  );
}

export class HomeScreen extends React.Component<HomeScreenProps> {
  static options() {
    return {
      topBar: {
        title: {
          text: 'Ordenes de servicio',
        },
        leftButtons: {
          id: 'sideMenu',
          icon: Icon.getImageSourceSync('navicon', 24, '#fff'),
        },
      },
    };
  }

  constructor(props: any) {
    super(props);
    Navigation.events().bindComponent(this);
  }

  render() {
    return <Home viewId={this.props.componentId} viewName={'Order'} />;
  }

  navigationButtonPressed({ buttonId }: { buttonId: string }) {
    if (buttonId == 'sideMenu') {
      this.props.componentId;
      Navigation.mergeOptions(this.props.componentId, {
        sideMenu: {
          left: {
            visible: true,
          },
        },
      });
    }
  }
}
