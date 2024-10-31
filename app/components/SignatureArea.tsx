import React, { useState, useEffect, useRef } from 'react';
import { Button, PermissionsAndroid, View } from 'react-native';
import SignatureCapture from 'react-native-signature-capture';
import Orientation from 'react-native-orientation';
import { styles } from '../styles/app';

export const SignatureArea = (props: any) => {
  const signatureRef: any = useRef(null);

  const handleImageSave = (result: any) => {
    props.setSignature(result.encoded);
  };

  return (
    <View style={styles.modalBackground}>
      <View style={styles.modalContainer}>
        <SignatureCapture
          style={styles.signature}
          ref={signatureRef}
          onSaveEvent={(result) => {
            handleImageSave(result);
            Orientation.lockToPortrait();
            props.setModalVisible(false);
          }}
          saveImageFileInExtStorage={false}
          showNativeButtons={true}
          showTitleLabel={false}
        />
      </View>
    </View>
  );
};
