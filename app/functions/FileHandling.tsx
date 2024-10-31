import * as fs from 'react-native-fs';

export const readFile = async (filename: string) => {
  try {
    const filePath = fs.DocumentDirectoryPath + filename;
    const fileContent = await fs.readFile(filePath, 'utf8');
    return JSON.parse(fileContent);
  } catch (error) {
    return [];
  }
};

export const writeFile = async (data: any, filename: string) => {
  try {
    const filePath = fs.DocumentDirectoryPath + filename;
    await fs.writeFile(filePath, JSON.stringify(data), 'utf8');
    //const directoryContent = await fs.readdir(fs.DocumentDirectoryPath);
    //console.log('Contenido del directorio:', directoryContent);
  } catch (error) {
    console.error('Error al escribir en el archivo JSON:', error);
  }
};

export const deleteFile = async (filename: string) => {
  try {
    const filePath = fs.DocumentDirectoryPath + filename;
    await fs.unlink(filePath);
    console.log('Archivo local eliminado con éxito:', filePath);
  } catch (error) {
    console.error('Error al eliminar el archivo local:', error);
  }
};

export const cleanFile = async (filename: string) => {
  try {
    const filePath = fs.DocumentDirectoryPath + filename;
    const fileExists = await fs.exists(filePath);
    if (fileExists) {
      await fs.writeFile(filePath, '', 'utf8');
    }
  } catch (error) {
    console.error('Error al intentar limpiar el contenido del archivo:', error);
  }
};
