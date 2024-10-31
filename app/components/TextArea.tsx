import React, {useState} from 'react';
import {Text, TextInput, View} from 'react-native';
import styles from './styles/textarea';

export const TextArea = (props: any) => {
  return (
    <View style={styles.inputGroup}>
      <Text style={styles.label}>{props.title}:</Text>
      <TextInput
        style={styles.input}
        onChangeText={props.setText}
        value={props.text}
        autoCapitalize="none"
        textAlignVertical="top"
        multiline={true}
        numberOfLines={5}
      />
    </View>
  );
};
