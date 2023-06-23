import axios from 'axios';

const sendPostRequest = async (url = '', formData = {}, headers = {}) => {
  try {
    const response = await axios.post(url, formData, {
      method: 'POST',
      mode: 'cors',
      cache: 'no-cache',
      credentials: 'same-origin',
      headers: {
        ...headers,
      },
      redirect: 'follow',
      referrerPolicy: 'no-referrer',
    });

    return response.data;
  } catch (error) {
    console.error('Error:', error);
    throw error;
  }
};


const sendGetRequest = async (url = '', headers = {}, init = {}) => {
  try {
    const response = await axios.get(url, {
      ...init,
      method: 'GET',
      mode: 'cors',
      cache: 'no-cache',
      credentials: 'same-origin',
      headers: {
        ...headers,
      },
      redirect: 'follow',
      referrerPolicy: 'no-referrer',
    });

    return response.data;
  } catch (error) {
    console.error('Error:', error);
    throw error;
  }
};

const sendDeleteRequest = async (url = '', headers = {}) => {
  try {
    const response = await axios.delete(url, {
      method: 'DELETE',
      mode: 'cors',
      cache: 'no-cache',
      credentials: 'same-origin',
      headers: {
        ...headers,
      },
      redirect: 'follow',
      referrerPolicy: 'no-referrer',
    });

    return response.data;
  } catch (error) {
    console.error('Error:', error);
    throw error;
  }
};

export { sendPostRequest, sendGetRequest, sendDeleteRequest };